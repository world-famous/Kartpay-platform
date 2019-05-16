## KartPay
## Installation steps

- **[clone This Repo]()**
- **[change permission of storage directory(write permissions)]()**
- **[copy .env.example to .env ]()**
- **[composer install]()**
- **[php artisan config:cache]**
also please be sure your server meet the laravel's requirement
 https://laravel.com/docs/5.4#installation
 
# Notes:

* Users
 * User Model is for panel/admin users
 * Merchant Model is for merchant users
* Namespace
 * for merchant controllers, put it on Merchant folder
 * for admin controllers, also on Admin folder
* Mailing
 * please use queue for better app performance
 * use mailable as much as possible
 
# Enable/Disable debugbar:
 * Open in the browser: http://kartpay.in/enable_debugbar/{false/true}. 'true' for enable and 'false' to disable debug bar.
 
# Log Activity:
 * Add this on the top of your controller:
```bash
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
```
 * Add this on the inside class of your controller:
```bash
 use LogsActivity;
``` 
 *Example to log data, put this syntax inside the method:
```bash
//ACTIVITY LOG
activity($this->eventUpdate)->causedBy($user)->withProperties([
																'attributes' => 
																[
																	'avatar_file_name' => $imageFileName,									
																],
																'old' => 
																[
																	'avatar_file_name' => $user->avatar_file_name,
																],
															])->log($this->updateAvatarSuccess);
//END ACTIVITY LOG
``` 
Note:
 * $this->eventUpdate = Log name (string).
 * causedBy($user) = User model that cause the transaction (Model).
 * withProperties([ ]) = Contains old data and new data {Optional} (Array).
 * $this->updateAvatarSuccess = Log description (string)
