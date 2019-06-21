#!/bin/bash
echo "10.201.102.32   uat-hrisapi.suncity-group.com" >> /etc/hosts
exec supervisord -n
