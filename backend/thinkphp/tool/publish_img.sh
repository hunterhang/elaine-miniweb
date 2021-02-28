#!/bin/sh
#usage: sh a.sh a.txt
GitPath=/data/www/admin/PublishImgForWelaine/img
TargetPath=/data/www/welaine_thinkphp/ui/img_show/
TargetFile=$1
echo $TargetFile
cd $TargetPath && git pull && cp -f $GitPath/$TargetFile $TargetPath && cd - 
