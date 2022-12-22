<?php //e03b9b0dd8112778d88354fbd46141cb
/** @noinspection all */

namespace App\Models {

    use Database\Factories\UserFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\MorphToMany;
    use Illuminate\Notifications\DatabaseNotification;
    use Illuminate\Notifications\DatabaseNotificationCollection;
    use Illuminate\Support\Carbon;
    use Laravel\Sanctum\PersonalAccessToken;
    use LaravelIdea\Helper\App\Models\_IH_City_C;
    use LaravelIdea\Helper\App\Models\_IH_City_QB;
    use LaravelIdea\Helper\App\Models\_IH_Client_C;
    use LaravelIdea\Helper\App\Models\_IH_Client_QB;
    use LaravelIdea\Helper\App\Models\_IH_ContactPerson_C;
    use LaravelIdea\Helper\App\Models\_IH_ContactPerson_QB;
    use LaravelIdea\Helper\App\Models\_IH_Country_C;
    use LaravelIdea\Helper\App\Models\_IH_Country_QB;
    use LaravelIdea\Helper\App\Models\_IH_GmLeadsMailList_C;
    use LaravelIdea\Helper\App\Models\_IH_GmLeadsMailList_QB;
    use LaravelIdea\Helper\App\Models\_IH_Industry_C;
    use LaravelIdea\Helper\App\Models\_IH_Industry_QB;
    use LaravelIdea\Helper\App\Models\_IH_Leads_C;
    use LaravelIdea\Helper\App\Models\_IH_Leads_QB;
    use LaravelIdea\Helper\App\Models\_IH_MomJob_C;
    use LaravelIdea\Helper\App\Models\_IH_MomJob_QB;
    use LaravelIdea\Helper\App\Models\_IH_Mom_C;
    use LaravelIdea\Helper\App\Models\_IH_Mom_QB;
    use LaravelIdea\Helper\App\Models\_IH_NotesMaster_C;
    use LaravelIdea\Helper\App\Models\_IH_NotesMaster_QB;
    use LaravelIdea\Helper\App\Models\_IH_SystemLog_C;
    use LaravelIdea\Helper\App\Models\_IH_SystemLog_QB;
    use LaravelIdea\Helper\App\Models\_IH_System_logs_C;
    use LaravelIdea\Helper\App\Models\_IH_System_logs_QB;
    use LaravelIdea\Helper\App\Models\_IH_TempLeads_C;
    use LaravelIdea\Helper\App\Models\_IH_TempLeads_QB;
    use LaravelIdea\Helper\App\Models\_IH_User_C;
    use LaravelIdea\Helper\App\Models\_IH_User_QB;
    use LaravelIdea\Helper\Illuminate\Notifications\_IH_DatabaseNotification_QB;
    use LaravelIdea\Helper\Laravel\Sanctum\_IH_PersonalAccessToken_C;
    use LaravelIdea\Helper\Laravel\Sanctum\_IH_PersonalAccessToken_QB;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Permission_C;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Permission_QB;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Role_C;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Role_QB;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Models\Role;

    /**
     * @property int $id
     * @property string $city_name
     * @property int $country_id
     * @property int|null $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Country $country
     * @method BelongsTo|_IH_Country_QB country()
     * @method static _IH_City_QB onWriteConnection()
     * @method _IH_City_QB newQuery()
     * @method static _IH_City_QB on(null|string $connection = null)
     * @method static _IH_City_QB query()
     * @method static _IH_City_QB with(array|string $relations)
     * @method _IH_City_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_City_C|City[] all()
     * @ownLinks country_id,\App\Models\Country,id
     * @foreignLinks id,\App\Models\User,city_id|id,\App\Models\Client,city_id
     * @mixin _IH_City_QB
     */
    class City extends Model {}

    /**
     * @property int $id
     * @property int|null $lead_id
     * @property string|null $company_name
     * @property int|null $industry_id
     * @property int|null $country_id
     * @property int|null $city_id
     * @property string|null $address
     * @property string|null $post_box_no
     * @property string|null $phone_no
     * @property string|null $email
     * @property string|null $website_name
     * @property string|null $sort_description
     * @property int|null $active_status
     * @property int|null $manage_by
     * @property int $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property City|null $city
     * @method BelongsTo|_IH_City_QB city()
     * @property Country|null $country
     * @method BelongsTo|_IH_Country_QB country()
     * @property Industry|null $industry
     * @method BelongsTo|_IH_Industry_QB industry()
     * @method static _IH_Client_QB onWriteConnection()
     * @method _IH_Client_QB newQuery()
     * @method static _IH_Client_QB on(null|string $connection = null)
     * @method static _IH_Client_QB query()
     * @method static _IH_Client_QB with(array|string $relations)
     * @method _IH_Client_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Client_C|Client[] all()
     * @ownLinks lead_id,\App\Models\Leads,id|industry_id,\App\Models\Industry,id|country_id,\App\Models\Country,id|city_id,\App\Models\City,id
     * @foreignLinks id,\App\Models\ContactPerson,client_id|id,\App\Models\Mom,client_id
     * @mixin _IH_Client_QB
     */
    class Client extends Model {}

    /**
     * @property int $id
     * @property int $client_id
     * @property string|null $name
     * @property string|null $department
     * @property string|null $designation
     * @property string|null $email
     * @property string|null $mobile_no
     * @property Carbon|null $dob
     * @property int $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_ContactPerson_QB onWriteConnection()
     * @method _IH_ContactPerson_QB newQuery()
     * @method static _IH_ContactPerson_QB on(null|string $connection = null)
     * @method static _IH_ContactPerson_QB query()
     * @method static _IH_ContactPerson_QB with(array|string $relations)
     * @method _IH_ContactPerson_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_ContactPerson_C|ContactPerson[] all()
     * @ownLinks client_id,\App\Models\Client,id
     * @mixin _IH_ContactPerson_QB
     */
    class ContactPerson extends Model {}

    /**
     * @property int $id
     * @property string $country_name
     * @property int|null $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property _IH_City_C|City[] $cities
     * @property-read int $cities_count
     * @method HasMany|_IH_City_QB cities()
     * @method static _IH_Country_QB onWriteConnection()
     * @method _IH_Country_QB newQuery()
     * @method static _IH_Country_QB on(null|string $connection = null)
     * @method static _IH_Country_QB query()
     * @method static _IH_Country_QB with(array|string $relations)
     * @method _IH_Country_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Country_C|Country[] all()
     * @foreignLinks id,\App\Models\User,country_id|id,\App\Models\City,country_id|id,\App\Models\Client,country_id
     * @mixin _IH_Country_QB
     */
    class Country extends Model {}

    /**
     * @property int $id
     * @property int $lead_id
     * @property string|null $name
     * @property string|null $country
     * @property bool $is_mail_sent
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_GmLeadsMailList_QB onWriteConnection()
     * @method _IH_GmLeadsMailList_QB newQuery()
     * @method static _IH_GmLeadsMailList_QB on(null|string $connection = null)
     * @method static _IH_GmLeadsMailList_QB query()
     * @method static _IH_GmLeadsMailList_QB with(array|string $relations)
     * @method _IH_GmLeadsMailList_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_GmLeadsMailList_C|GmLeadsMailList[] all()
     * @ownLinks lead_id,\App\Models\Leads,id
     * @mixin _IH_GmLeadsMailList_QB
     */
    class GmLeadsMailList extends Model {}

    /**
     * @property int $id
     * @property string $industry_name
     * @property int $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_Industry_QB onWriteConnection()
     * @method _IH_Industry_QB newQuery()
     * @method static _IH_Industry_QB on(null|string $connection = null)
     * @method static _IH_Industry_QB query()
     * @method static _IH_Industry_QB with(array|string $relations)
     * @method _IH_Industry_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Industry_C|Industry[] all()
     * @foreignLinks id,\App\Models\TempLeads,industry_id|id,\App\Models\Client,industry_id
     * @mixin _IH_Industry_QB
     */
    class Industry extends Model {}

    /**
     * @property int $id
     * @property string $spoken_with
     * @property int|null $temp_lead_id
     * @property int|null $bdm_id
     * @property int|null $lead_status
     * @property string|null $contact_no
     * @property string|null $email
     * @property bool|null $is_requirement
     * @property string|null $basic_requirement
     * @property int $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property TempLeads|null $tempLead
     * @method BelongsTo|_IH_TempLeads_QB tempLead()
     * @method static _IH_Leads_QB onWriteConnection()
     * @method _IH_Leads_QB newQuery()
     * @method static _IH_Leads_QB on(null|string $connection = null)
     * @method static _IH_Leads_QB query()
     * @method static _IH_Leads_QB with(array|string $relations)
     * @method _IH_Leads_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Leads_C|Leads[] all()
     * @ownLinks temp_lead_id,\App\Models\TempLeads,id
     * @foreignLinks id,\App\Models\Client,lead_id|id,\App\Models\GmLeadsMailList,lead_id
     * @mixin _IH_Leads_QB
     */
    class Leads extends Model {}

    /**
     * @property int $id
     * @property int|null $client_id
     * @property Carbon|null $meeting_date
     * @property string|null $contact_person
     * @property string|null $minutes_of_meeting
     * @property string|null $bde_feedback
     * @property string|null $mom_type
     * @property string|null $followup
     * @property int|null $share_user_id
     * @property Carbon|null $next_followup_date
     * @property Carbon|null $next_followup_time
     * @property string|null $client_status
     * @property int|null $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_Mom_QB onWriteConnection()
     * @method _IH_Mom_QB newQuery()
     * @method static _IH_Mom_QB on(null|string $connection = null)
     * @method static _IH_Mom_QB query()
     * @method static _IH_Mom_QB with(array|string $relations)
     * @method _IH_Mom_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Mom_C|Mom[] all()
     * @ownLinks client_id,\App\Models\Client,id
     * @foreignLinks id,\App\Models\MomJob,mom_id
     * @mixin _IH_Mom_QB
     */
    class Mom extends Model {}

    /**
     * @property int $id
     * @property int|null $mom_id
     * @property Carbon|null $j_date
     * @property string|null $job_category
     * @property int|null $quantity
     * @property string|null $job_description
     * @property string|null $job_status
     * @property Carbon|null $status_date
     * @property int|null $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_MomJob_QB onWriteConnection()
     * @method _IH_MomJob_QB newQuery()
     * @method static _IH_MomJob_QB on(null|string $connection = null)
     * @method static _IH_MomJob_QB query()
     * @method static _IH_MomJob_QB with(array|string $relations)
     * @method _IH_MomJob_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_MomJob_C|MomJob[] all()
     * @ownLinks mom_id,\App\Models\Mom,id
     * @mixin _IH_MomJob_QB
     */
    class MomJob extends Model {}

    /**
     * @property int $id
     * @property string|null $title
     * @property string|null $description
     * @property Carbon|null $reminder_at
     * @property int $manage_by
     * @property int $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_NotesMaster_QB onWriteConnection()
     * @method _IH_NotesMaster_QB newQuery()
     * @method static _IH_NotesMaster_QB on(null|string $connection = null)
     * @method static _IH_NotesMaster_QB query()
     * @method static _IH_NotesMaster_QB with(array|string $relations)
     * @method _IH_NotesMaster_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_NotesMaster_C|NotesMaster[] all()
     * @mixin _IH_NotesMaster_QB
     */
    class NotesMaster extends Model {}

    /**
     * @property int $id
     * @property int $user_id
     * @property int|null $action_id
     * @property int|null $action_to_id
     * @property int|null $call_type
     * @property int|null $call_status
     * @property string|null $module
     * @property string|null $action_type
     * @property string|null $description
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_SystemLog_QB onWriteConnection()
     * @method _IH_SystemLog_QB newQuery()
     * @method static _IH_SystemLog_QB on(null|string $connection = null)
     * @method static _IH_SystemLog_QB query()
     * @method static _IH_SystemLog_QB with(array|string $relations)
     * @method _IH_SystemLog_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_SystemLog_C|SystemLog[] all()
     * @ownLinks user_id,\App\Models\User,id
     * @mixin _IH_SystemLog_QB
     */
    class SystemLog extends Model {}

    /**
     * @property int $id
     * @property int $user_id
     * @property int|null $action_id
     * @property int|null $action_to_id
     * @property int|null $call_type
     * @property int|null $call_status
     * @property string|null $module
     * @property string|null $action_type
     * @property string|null $description
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_System_logs_QB onWriteConnection()
     * @method _IH_System_logs_QB newQuery()
     * @method static _IH_System_logs_QB on(null|string $connection = null)
     * @method static _IH_System_logs_QB query()
     * @method static _IH_System_logs_QB with(array|string $relations)
     * @method _IH_System_logs_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_System_logs_C|System_logs[] all()
     * @ownLinks user_id,\App\Models\User,id
     * @mixin _IH_System_logs_QB
     */
    class System_logs extends Model {}

    /**
     * @property int $id
     * @property string $company_name
     * @property string|null $company_phone_no
     * @property int|null $company_country_id
     * @property int|null $company_city_id
     * @property int|null $industry_id
     * @property string $company_email
     * @property string|null $department
     * @property string|null $designation
     * @property string|null $contact_person_name
     * @property string|null $contact_person_email
     * @property string|null $contact_person_phone
     * @property Carbon|null $dob
     * @property string|null $address
     * @property string|null $post_box_no
     * @property int|null $cp_country_id
     * @property int|null $cp_city_id
     * @property string|null $website_name
     * @property string|null $calling_status
     * @property Carbon|null $recalling_date
     * @property Carbon|null $last_call_date
     * @property string|null $call_type
     * @property int|null $tele_caller_id
     * @property int|null $last_tele_caller_id
     * @property bool $is_assigned
     * @property int|null $imported_by
     * @property int|null $created_by
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property City $city
     * @method BelongsTo|_IH_City_QB city()
     * @property Country $country
     * @method BelongsTo|_IH_Country_QB country()
     * @property Industry|null $industry
     * @method BelongsTo|_IH_Industry_QB industry()
     * @method static _IH_TempLeads_QB onWriteConnection()
     * @method _IH_TempLeads_QB newQuery()
     * @method static _IH_TempLeads_QB on(null|string $connection = null)
     * @method static _IH_TempLeads_QB query()
     * @method static _IH_TempLeads_QB with(array|string $relations)
     * @method _IH_TempLeads_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_TempLeads_C|TempLeads[] all()
     * @ownLinks industry_id,\App\Models\Industry,id
     * @foreignLinks id,\App\Models\Leads,temp_lead_id
     * @mixin _IH_TempLeads_QB
     */
    class TempLeads extends Model {}

    /**
     * @property int $id
     * @property string $name
     * @property string $email
     * @property Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $remember_token
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $reporting_user_id
     * @property int|null $created_by
     * @property int|null $country_id
     * @property int|null $city_id
     * @property int|null $modified_by
     * @property int|null $is_deleted
     * @property City|null $city
     * @method BelongsTo|_IH_City_QB city()
     * @property Country|null $country
     * @method BelongsTo|_IH_Country_QB country()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
     * @property-read int $notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB notifications()
     * @property _IH_Permission_C|Permission[] $permissions
     * @property-read int $permissions_count
     * @method MorphToMany|_IH_Permission_QB permissions()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $readNotifications
     * @property-read int $read_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB readNotifications()
     * @property _IH_Role_C|Role[] $roles
     * @property-read int $roles_count
     * @method MorphToMany|_IH_Role_QB roles()
     * @property _IH_PersonalAccessToken_C|PersonalAccessToken[] $tokens
     * @property-read int $tokens_count
     * @method MorphToMany|_IH_PersonalAccessToken_QB tokens()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $unreadNotifications
     * @property-read int $unread_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB unreadNotifications()
     * @method static _IH_User_QB onWriteConnection()
     * @method _IH_User_QB newQuery()
     * @method static _IH_User_QB on(null|string $connection = null)
     * @method static _IH_User_QB query()
     * @method static _IH_User_QB with(array|string $relations)
     * @method _IH_User_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_User_C|User[] all()
     * @ownLinks country_id,\App\Models\Country,id|city_id,\App\Models\City,id
     * @foreignLinks id,\App\Models\System_logs,user_id
     * @mixin _IH_User_QB
     * @method static UserFactory factory(...$parameters)
     */
    class User extends Model {}
}
