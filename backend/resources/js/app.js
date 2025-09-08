import './bootstrap';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

import App from './App.vue';
import Dashboard from './pages/Dashboard.vue';
import FinalTest from './pages/FinalTest.vue';
import SelfTest from './pages/SelfTest.vue';

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const saved = localStorage.getItem('token');
if (saved) axios.defaults.headers.common['Authorization'] = `Bearer ${saved}`;

const store = createStore({
    modules: {
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
                        const { data } = await axios.get('/api/topics/unlocked');
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
                    const { data } = await axios.post('/api/tests/final', { topic_id, question_count, time_limit_seconds, grade });
                    commit('setCurrent', data);
                    return data;
                },
                async fetchQuestions({ state, commit }) {
                    if (!state.current) return [];
                    const { data } = await axios.get(`/api/tests/${state.current.id}/questions`);
                    commit('setQuestions', data);
                    return data;
                },
                async submitFinal({ state, commit }, { answers }) {
                    commit('setSubmitting', true);
                    try {
                        const { data } = await axios.post(`/api/tests/${state.current.id}/final/submit`, { answers });
                        commit('setCurrent', data);
                        return data;
                    } finally {
                        commit('setSubmitting', false);
                    }
                },
                async startSelf({ commit }, { topic_id, question_count, grade }) {
                    const { data } = await axios.post('/api/tests/self', { topic_id, question_count, grade });
                    commit('setCurrent', data);
                    return data;
                },
                async submitSelf({ state, commit }, { answers }) {
                    commit('setSubmitting', true);
                    try {
                        const { data } = await axios.post(`/api/tests/${state.current.id}/self/submit`, { answers });
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
            state: () => ({ xp: 0, badges: [], progress: [] }),
            mutations: {
                set(state, payload) {
                    state.xp = payload.xp;
                    state.badges = payload.badges;
                    state.progress = payload.progress;
                },
            },
            actions: {
                async fetch({ commit }) {
                    const { data } = await axios.get('/api/user/progress/topics');
                    commit('set', data);
                },
            },
        },
    },
});

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'dashboard', component: Dashboard },
        { path: '/final-test', name: 'final-test', component: FinalTest },
        { path: '/self-test', name: 'self-test', component: SelfTest },
    ],
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const requiresAuth = ['final-test', 'self-test'].includes(to.name);
    if (requiresAuth && !token) return next({ name: 'dashboard' });
    next();
});

const app = createApp(App);
app.use(store);
app.use(router);
app.mount('#app');

