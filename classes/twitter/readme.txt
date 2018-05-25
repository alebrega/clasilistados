# Twitter.class.php

This class is a very simple class (~50 lines of code) that updates a twitter
user's status. It does so using fsockopen() which is included in PHP by default.
Thus, it does not require any additional modules (like cURL or HTTP).

Check out example.php included with this package to see how you are supposed to
use this class.

This class was tested on PHP 5.2.6 and it should work on any PHP 5.x. Compatibility for PHP 4 is found in Twitter.class.php4.