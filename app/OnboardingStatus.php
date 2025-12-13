<?php

namespace App;

enum OnboardingStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
}
