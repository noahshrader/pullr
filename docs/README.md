pullr
=====


####Cron tasks

to check and send emails every 1 minute
* `* * * * * /www/pullr/yii mail/send`

to check and remove user who decide to deactive account if 30 days expired
* `*/30 * * * * /www/pullr/yii cron/userdelete`
