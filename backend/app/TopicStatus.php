<?php

namespace App;

enum TopicStatus: string
{
    case Locked = 'locked';
    case Unlocked = 'unlocked';
    case Passed = 'passed';
    case Failed = 'failed';
}
