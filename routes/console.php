<?php

use Illuminate\Support\Facades\Schedule;


Schedule::command('aggregator:run')->everyTenMinutes();
