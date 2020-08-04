<?php  #  the first 4 keys/token should be requested at twitter - developer
$your_ConsumerKey       = ''; # example someting like  'zNtV7fUWPVuFwzVy0gJ2bQrZ';
$your_ConsumerSecret    = ''; # example someting like  'Q0HutScSkgXpzA76NMmCNFLXEpYNFr7rPAwRASsx';
$your_AccessToken       = ''; # example someting like  '1160569623944345BwIL54dfbzz9UdJxm2h4gjRTg5TNx';
$your_AccessTokenSecret = ''; # example someting like  'Rgpx9YwfthjXThx4nQxxxxEgjU2h9QO552fLZY9mZ9';
#
#  you need a string to use in the url to sent a tweet
#  use no special characters and only ascii, do not use 12345, 
$your_check             = '12345'; 
# 
# use a cron to call the twitter script   /pwsWD/PWS_tweet_this.php?check=XXXXXXXXXXX
# replace the XXXXXXXXXXX  with the value as in $your_check
#
