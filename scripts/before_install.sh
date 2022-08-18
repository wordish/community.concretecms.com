#!/bin/bash
set -euo pipefail

tempdir=/tmp/community/codedeployupload

[[ -d $tempdir ]] && rm -r $tempdir
mkdir -p $tempdir

mkdir /tmp/$DEPLOYMENT_ID

echo "export tempdir=\"$tempdir\"" > "/tmp/$DEPLOYMENT_ID/.cdvariables";

if [ "$APPLICATION_NAME" == "community.stage.concretecms.com" ]
then
  echo "export projectdir=\"/home/forge/community.stage.concretecms.com\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
  echo "export deploydir=\"/home/forge/community.stage.concretecms.com/releases/$DEPLOYMENT_ID\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
elif [ "$APPLICATION_NAME" == "community.concretecms.com" ]
then
  echo "export projectdir=\"/home/forge/community.concretecms.com\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
  echo "export deploydir=\"/home/forge/community.concretecms.com/releases/$DEPLOYMENT_ID\"" >> "/tmp/$DEPLOYMENT_ID/.cdvariables";
fi
