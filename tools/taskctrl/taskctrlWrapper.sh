#!/bin/bash

# log file
LOG="taskCtrlTrace/taskCtrlTrace`date +"%Y-%m-%d"`.Log"
# taskctrl log file
tmpLOG=taskCtrlTrace\\`date +"%Y-%m-%d"`.Log

#echo "start"
# dir move
cd $(cd $(dirname $0); pwd)

# start
echo "[`date +"%Y-%m-%d %H:%M:%S"`] taskCtrl start -----" >> $LOG

# execute
out=`java -jar taskctrl.jar $1 $2 $3 $4 $5`

ret=$?
echo -e "$out"
#echo -e "$ret"
if [ $ret -ne 0 ]; then
   #ls task*.Log | (read v;cat task*.Log >> taskCtrlTrace/$v;);
   cat $tmpLOG >> $LOG;

   rm -f $tmpLOG;
fi

echo -e "$out" | grep -v "^$"  >> $LOG

#echo "end"
echo "[`date +"%Y-%m-%d %H:%M:%S"`] taskCtrl end --------" >> $LOG
exit $ret
