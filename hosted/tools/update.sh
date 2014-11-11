#!/bin/sh

cd /home/webserver/public/heaven-craft.net/repo

printf "<b>Working Directory</b>\n"
pwd
printf "\n"

printf "<b>Update</b>\n"
git fetch --all
printf "\n"

printf "<b>Status</b>\n"
git status
printf "\n"

printf "<b>Update local revision</b>\n"
git pull
printf "\n"

printf "<b>Current revion info</b>\n"
git reset --hard origin/master
