
=== Unguessable Images ===
Contributors: phpdevca
Tags: images private
Donate link: http://www.phpdevelopment.ca/donate/
Requires at least: 4
Tested up to: 5.0.2
Requires PHP: 5

Replace default image filenames with unguessable random filenames

== Description ==
Want to keep your upload media private? This plugin replaces filenames with a string of  cryptographic random bytes. You can also include a specified number of characters from the original filename, and/or add a prefix and suffix to filenames.

== Installation ==
1. Install plugin.
2. Activate plugin.
3. That\'s it for default installation. Upload an image to the media library and the plugin will have changed the filename to a random string.
4. Optionally customise the plugin under  the \"Settings / Unguessable Images\" admin menu link.


== Frequently Asked Questions ==
Q. What does the plugin do?
A. Changes the filename of an uploaded image to include a random string of bytes, making it virtually impossible for an outsider to guess the URL of the image.

Q. Why does this matter?
A. It matters if you have private content on your site that you only want to be available to visitors who know the URL of your image.

Q. Can\'t people see the URL of images within the page source code?
A. Yes they can, if the page is accessible to them. Therefore, in order to keep your images private you will have to ensure you never include them in public pages. However if you privately publish a page, then an attacker would need access to that page to find out the URL of the images used on it.

Q. Are the images still publicly accessible if I use this plugin?
A. Yes the are. The point is that it would be virtually impossible for someone to guess the URLs to your images. However if the URL is provided to them by some other means, then they could still access the image once they know the URL.

Q. Are images *really* unguessable?
A. Practically, yes. However, as with any probability, there is an infinitesimally small chance that an attacker could guess the URL of one of your images, but the chances are so tiny as to be negligible. To give you an idea, the default strength is 16 bytes. That means there are 340282370000000000000000000000000000000 possible combinations for an attacker to guess. If it was possible for them to try 1,000,000 times per second, and you had 1 million images on the site,  they would still need 5,391,559,500,000,000,000 YEARS  to have a 50% chance of stumbling across one of them.

Q. Does this plugin guarantee that nobody could ever access my images without my authorization?
A. No. What this plugin does is make it statistically impossible for someone to guess the URL of an image. However, as discussed above, if someone finds out the URL of an image by some other means, they can still access it. Therefore, we suggest if you really want to ensure your images cannot possibly be accessed, use some form of authentication (e.g. HTTP authentication) instead.

Q. How is the URL generated?
A. Using the random_bytes function in PHP. There is a less-secure fallback if you do not have this function, which was introduced in PHP 7.

Q. What customisations can I do?
A. You can increase or decrease the length of the generated string. You may also include characters from the original filename, and add a prefix or suffix.
