#!/bin/bash
set -euo pipefail

tempdir=/tmp/codedeployupload

[[ -d $tempdir ]] && rm -r $tempdir
mkdir -p $tempdir

if [ "$APPLICATION_NAME" == "community.stage.concretecms.com" ]
then
  echo "export projectdir=\"/home/forge/community.stage.concretecms.com\"" > "/tmp/.cdvariables";
  echo "export deploydir=\"/home/forge/community.stage.concretecms.com/releases/$DEPLOYMENT_ID\"" >> "/tmp/.cdvariables";
elif [ "$APPLICATION_NAME" == "community.concretecms.com" ]
then
  echo "export projectdir=\"/home/forge/community.concretecms.com\"" > "/tmp/.cdvariables";
  echo "export deploydir=\"/home/forge/community.concretecms.com/releases/$DEPLOYMENT_ID\"" >> "/tmp/.cdvariables";
fi
