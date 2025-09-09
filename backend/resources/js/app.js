import './bootstrap';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

import App from './App.vue';
import Dashboard from './pages/Dashboard.vue';
import FinalTest from './pages/FinalTest.vue';
import SelfTest from './pages/SelfTest.vue';
import TopicDetail from './pages/TopicDetail.vue';
import Profile from './pages/Profile.vue';
import SettingsPage from './pages/Settings.vue';

// Import glassmorphic components
import * as GlassComponents from './components';

// Import Lucide icons
import { 
  RefreshCw, 
  LogOut, 
  BookOpen, 
  Target, 
  Home, 
  Trophy, 
  Award, 
  Star,
  CheckCircle,
  XCircle,
  AlertCircle,
  Info,
  User,
  Settings,
  BarChart3,
  Flame,
  Lock,
  Circle,
  ChevronRight,
  ChevronLeft
} from 'lucide-vue-next';

axios.defaults.baseURL = '/api';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';
const saved = localStorage.getItem('token');
if (saved) axios.defaults.headers.common['Authorization'] = `Bearer ${saved}`;

const store = createStore({
    modules: {
        auth: {
            namespaced: true,
            state: () => ({ 
                user: null, 
                token: localStorage.getItem('token'),
                isAuthenticated: !!localStorage.getItem('token')
            }),
            mutations: {
                setUser(state, user) { 
                    state.user = user; 
                    state.isAuthenticated = !!user;
                },
                setToken(state, token) { 
                    state.token = token;
                    state.isAuthenticated = !!token;
                    if (token) {
                        localStorage.setItem('token', token);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    } else {
                        localStorage.removeItem('token');
                        delete axios.defaults.headers.common['Authorization'];
                    }
                },
                logout(state) {
                    state.user = null;
                    state.token = null;
                    state.isAuthenticated = false;
                    localStorage.removeItem('token');
                    delete axios.defaults.headers.common['Authorization'];
                }
            },
            actions: {
                async login({ commit }, { email, password }) {
                    try {
                        console.log('Attempting login with:', { email, password });
                        const { data } = await axios.post('/auth/login', { email, password });
                        console.log('Login successful:', data);
                        commit('setToken', data.token);
                        commit('setUser', data.user);
                        return data;
                    } catch (error) {
                        console.error('Login error:', error.response?.data || error.message);
                        throw error;
                    }
                },
                async register({ commit }, { name, email, password, password_confirmation }) {
                    try {
                        const { data } = await axios.post('/auth/register', { 
                            name, 
                            email, 
                            password, 
                            password_confirmation 
                        });
                        commit('setToken', data.token);
                        commit('setUser', data.user);
                        return data;
                    } catch (error) {
                        throw error;
                    }
                },
                async logout({ commit }) {
                    try {
                        await axios.post('/auth/logout');
                    } catch (error) {
                        // Continue with logout even if API call fails
                    } finally {
                        commit('logout');
                    }
                },
                async fetchUser({ commit }) {
                    try {
                        const { data } = await axios.get('/user');
                        commit('setUser', data);
                        return data;
                    } catch (error) {
                        commit('logout');
                        throw error;
                    }
                }
            }
        },
        toast: {
            namespaced: true,
            state: () => ({ toasts: [] }),
            mutations: {
                add(state, toast) {
                    state.toasts.push({
                        id: Date.now().toString() + Math.random().toString(36).substr(2, 9),
                        ...toast
                    })
                },
                remove(state, id) {
                    state.toasts = state.toasts.filter(toast => toast.id !== id)
                },
                clear(state) {
                    state.toasts = []
                }
            },
            actions: {
                show({ commit }, toast) {
                    commit('add', toast)
                },
                success({ commit }, { title, message, duration = 5000 }) {
                    commit('add', { type: 'success', title, message, duration })
                },
                error({ commit }, { title, message, duration = 7000 }) {
                    commit('add', { type: 'error', title, message, duration })
                },
                warning({ commit }, { title, message, duration = 6000 }) {
                    commit('add', { type: 'warning', title, message, duration })
                },
                info({ commit }, { title, message, duration = 5000 }) {
                    commit('add', { type: 'info', title, message, duration })
                }
            }
        },
        topics: {
            namespaced: true,
            state: () => ({ list: [], loading: false }),
            mutations: {
                set(state, items) { state.list = items; },
                setLoading(state, v) { state.loading = v; },
            },
            actions: {
                async fetch({ commit }) {
                    commit('setLoading', true);
                    try {
                        const { data } = await axios.get('/topics/unlocked');
                        commit('set', data);
                    } finally {
                        commit('setLoading', false);
                    }
                },
                async fetchAll({ commit }) {
                    commit('setLoading', true);
                    try {
                        const { data } = await axios.get('/topics/all');
                        commit('set', data);
                    } finally {
                        commit('setLoading', false);
                    }
                },
            },
        },
        tests: {
            namespaced: true,
            state: () => ({ current: null, questions: [], submitting: false }),
            mutations: {
                setCurrent(state, t) { state.current = t; },
                setQuestions(state, q) { state.questions = q; },
                setSubmitting(state, v) { state.submitting = v; },
            },
            actions: {
                async startFinal({ commit }, { topic_id, question_count, time_limit_seconds, grade }) {
                    const { data } = await axios.post('/tests/final', { topic_id, question_count, time_limit_seconds, grade });
                    commit('setCurrent', data);
                    return data;
                },
                async fetchQuestions({ state, commit }) {
                    if (!state.current) return [];
                    const { data } = await axios.get(`/tests/${state.current.id}/questions`);
                    commit('setQuestions', data);
                    return data;
                },
                async submitFinal({ state, commit }, { answers }) {
                    commit('setSubmitting', true);
                    try {
                        const { data } = await axios.post(`/tests/${state.current.id}/final/submit`, { answers });
                        commit('setCurrent', data);
                        return data;
                    } finally {
                        commit('setSubmitting', false);
                    }
                },
                async startSelf({ commit }, { topic_id, question_count, grade }) {
                    const { data } = await axios.post('/tests/self', { topic_id, question_count, grade });
                    commit('setCurrent', data);
                    return data;
                },
                async submitSelf({ state, commit }, { answers }) {
                    commit('setSubmitting', true);
                    try {
                        const { data } = await axios.post(`/tests/${state.current.id}/self/submit`, { answers });
                        commit('setCurrent', data.test);
                        return data;
                    } finally {
                        commit('setSubmitting', false);
                    }
                },
            },
        },
        gamification: {
            namespaced: true,
            state: () => ({ 
                xp: 0, 
                badges: [], 
                progress: [], 
                currentStreak: 0,
                totalTestsCompleted: 0,
                recentActivity: []
            }),
            mutations: {
                set(state, payload) {
                    state.xp = payload.xp || 0;
                    state.badges = payload.badges || [];
                    state.progress = payload.progress || [];
                    state.currentStreak = payload.currentStreak || 0;
                    state.totalTestsCompleted = payload.totalTestsCompleted || 0;
                    state.recentActivity = payload.recentActivity || [];
                },
            },
            actions: {
                async fetch({ commit }) {
                    const { data } = await axios.get('/user/progress/topics');
                    commit('set', data);
                },
            },
        },
        profile: {
            namespaced: true,
            state: () => ({ 
                profileData: null, 
                recentActivity: [], 
                completedTests: 0, 
                currentStreak: 0 
            }),
            mutations: {
                setProfileData(state, data) {
                    state.profileData = data;
                },
                setRecentActivity(state, activity) {
                    state.recentActivity = activity;
                },
                setCompletedTests(state, count) {
                    state.completedTests = count;
                },
                setCurrentStreak(state, streak) {
                    state.currentStreak = streak;
                },
            },
            actions: {
                async fetchProfileData({ commit }) {
                    const { data } = await axios.get('/profile');
                    commit('setProfileData', data);
                    return data;
                },
                async updateProfile({ commit }, profileData) {
                    const { data } = await axios.put('/profile', profileData);
                    commit('setProfileData', data);
                    return data;
                },
            },
        },
        settings: {
            namespaced: true,
            state: () => ({ 
                settings: null, 
                notificationSettings: {
                    email_notifications: true,
                    test_reminders: true,
                    weekly_progress_emails: true,
                    achievement_notifications: true
                },
                preferences: {
                    language: 'lv',
                    timezone: 'Europe/Riga',
                    custom_preferences: {}
                }
            }),
            mutations: {
                setSettings(state, settings) {
                    state.settings = settings;
                    if (settings.notification_settings) {
                        state.notificationSettings = settings.notification_settings;
                    }
                    if (settings.preferences) {
                        state.preferences = settings.preferences;
                    }
                },
                setNotificationSettings(state, settings) {
                    state.notificationSettings = settings;
                },
                setPreferences(state, preferences) {
                    state.preferences = preferences;
                },
            },
            actions: {
                async fetchSettings({ commit }) {
                    const { data } = await axios.get('/settings');
                    commit('setSettings', data);
                    return data;
                },
                async updateAccount({ commit }, accountData) {
                    const { data } = await axios.put('/settings/account', accountData);
                    commit('setSettings', data);
                    return data;
                },
                async updatePassword({ commit }, passwordData) {
                    const { data } = await axios.put('/settings/password', passwordData);
                    return data;
                },
                async updateNotificationSettings({ commit }, settings) {
                    const { data } = await axios.put('/settings/notifications', settings);
                    commit('setNotificationSettings', settings);
                    return data;
                },
                async updatePreferences({ commit }, preferences) {
                    const { data } = await axios.put('/settings/preferences', preferences);
                    commit('setPreferences', preferences);
                    return data;
                },
                async deleteAccount({ commit }) {
                    await axios.delete('/settings/account');
                    commit('auth/logout', null, { root: true });
                },
                async exportData({ commit }) {
                    const { data } = await axios.get('/settings/export');
                    // Create download link
                    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = `uzdevumu-meistars-data-${new Date().toISOString().split('T')[0]}.json`;
                    link.click();
                    window.URL.revokeObjectURL(url);
                },
                async clearCache({ commit }) {
                    const { data } = await axios.post('/settings/clear-cache');
                    return data;
                },
                async getLanguages({ commit }) {
                    const { data } = await axios.get('/settings/languages');
                    return data;
                },
                async getTimezones({ commit }) {
                    const { data } = await axios.get('/settings/timezones');
                    return data;
                },
            },
        },
    },
});

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'dashboard', component: Dashboard },
        { path: '/topic/:id', name: 'topic-detail', component: TopicDetail },
        { path: '/final-test', name: 'final-test', component: FinalTest },
        { path: '/self-test', name: 'self-test', component: SelfTest },
        { path: '/profile', name: 'profile', component: Profile },
        { path: '/settings', name: 'settings', component: SettingsPage },
    ],
});

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem('token');
    const requiresAuth = ['topic-detail', 'final-test', 'self-test', 'profile', 'settings'].includes(to.name);
    
    if (requiresAuth && !token) {
        return next({ name: 'dashboard' });
    }
    
    // If user has token but no user data, fetch it
    if (token && !store.state.auth.user) {
        try {
            await store.dispatch('auth/fetchUser');
        } catch (error) {
            // Token is invalid, redirect to dashboard
            store.commit('auth/logout');
            return next({ name: 'dashboard' });
        }
    }
    
    next();
});

const app = createApp(App);

// Register glassmorphic components globally
Object.entries(GlassComponents).forEach(([name, component]) => {
    app.component(name, component);
});

// Register Lucide icons globally
const icons = {
  RefreshCw, LogOut, BookOpen, Target, Home, Trophy, Award, Star,
  CheckCircle, XCircle, AlertCircle, Info, User, Settings, BarChart3,
  Flame, Lock, Circle, ChevronRight, ChevronLeft
};

Object.entries(icons).forEach(([name, component]) => {
    app.component(name, component);
});

app.use(store);
app.use(router);

// Initialize authentication state on app start
if (localStorage.getItem('token')) {
    store.dispatch('auth/fetchUser').catch(() => {
        // Token is invalid, clear it
        store.commit('auth/logout');
    });
}

app.mount('#app');

