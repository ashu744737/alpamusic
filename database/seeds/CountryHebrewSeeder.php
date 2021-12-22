<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryHebrewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = array(
            array('code' => 'US', 'en_name' => 'United States', 'hr_name' => 'ארצות הברית'),
            array('code' => 'CA', 'en_name' => 'Canada', 'hr_name' => 'קנדה'),
            array('code' => 'AF', 'en_name' => 'Afghanistan', 'hr_name' => 'אפגניסטן'),
            array('code' => 'AL', 'en_name' => 'Albania', 'hr_name' => 'איי אולנד'),
            array('code' => 'DZ', 'en_name' => 'Algeria', 'hr_name' => 'אלג׳יריה'),
            array('code' => 'AS', 'en_name' => 'American Samoa', 'hr_name' => 'סמואה האמריקנית'),
            array('code' => 'AD', 'en_name' => 'Andorra', 'hr_name' => 'אנדורה'),
            array('code' => 'AO', 'en_name' => 'Angola', 'hr_name' => 'אנגולה'),
            array('code' => 'AI', 'en_name' => 'Anguilla', 'hr_name' => 'אנגווילה'),
            array('code' => 'AQ', 'en_name' => 'Antarctica', 'hr_name' => 'אנטארקטיקה'),
            array('code' => 'AG', 'en_name' => 'Antigua and/or Barbuda', 'hr_name' => 'אנטיגואה וברבודה'),
            array('code' => 'AR', 'en_name' => 'Argentina', 'hr_name' => 'ארגנטינה'),
            array('code' => 'AM', 'en_name' => 'Armenia', 'hr_name' => 'אַרְמֶנִיָה'),
            array('code' => 'AW', 'en_name' => 'Aruba', 'hr_name' => 'ארובה'),
            array('code' => 'AU', 'en_name' => 'Australia', 'hr_name' => 'אוֹסטְרַלִיָה'),
            array('code' => 'AT', 'en_name' => 'Austria', 'hr_name' => 'אוֹסְטְרֵיָה'),
            array('code' => 'AZ', 'en_name' => 'Azerbaijan', 'hr_name' => "אזרבייז'אן"),
            array('code' => 'BS', 'en_name' => 'Bahamas', 'hr_name' => 'איי בהאמה'),
            array('code' => 'BH', 'en_name' => 'Bahrain', 'hr_name' => 'בחריין'),
            array('code' => 'BD', 'en_name' => 'Bangladesh', 'hr_name' => 'בנגלדש'),
            array('code' => 'BB', 'en_name' => 'Barbados', 'hr_name' => 'ברבדוס'),
            array('code' => 'BY', 'en_name' => 'Belarus', 'hr_name' => 'בלארוס'),
            array('code' => 'BE', 'en_name' => 'Belgium', 'hr_name' => 'בלגיה'),
            array('code' => 'BZ', 'en_name' => 'Belize', 'hr_name' => 'בליז'),
            array('code' => 'BJ', 'en_name' => 'Benin', 'hr_name' => 'בנין'),
            array('code' => 'BM', 'en_name' => 'Bermuda', 'hr_name' => 'ברמודה'),
            array('code' => 'BT', 'en_name' => 'Bhutan', 'hr_name' => 'בהוטן'),
            array('code' => 'BO', 'en_name' => 'Bolivia', 'hr_name' => 'בוליביה'),
            array('code' => 'BA', 'en_name' => 'Bosnia and Herzegovina', 'hr_name' => 'בוסניה הרצוגובינה'),
            array('code' => 'BW', 'en_name' => 'Botswana', 'hr_name' => 'בוטסוואנה'),
            array('code' => 'BV', 'en_name' => 'Bouvet Island', 'hr_name' => 'האי בובוט'),
            array('code' => 'BR', 'en_name' => 'Brazil', 'hr_name' => 'ברזיל'),
            array('code' => 'IO', 'en_name' => 'British lndian Ocean Territory', 'hr_name' => 'הטריטוריה הבריטית באוקיינוס ההודי'),
            array('code' => 'BN', 'en_name' => 'Brunei Darussalam', 'hr_name' => 'ברוניי דארוסלאם'),
            array('code' => 'BG', 'en_name' => 'Bulgaria', 'hr_name' => 'בולגריה'),
            array('code' => 'BF', 'en_name' => 'Burkina Faso', 'hr_name' => 'בורקינה פאסו'),
            array('code' => 'BI', 'en_name' => 'Burundi', 'hr_name' => 'בורונדי'),
            array('code' => 'KH', 'en_name' => 'Cambodia', 'hr_name' => 'קמבודיה'),
            array('code' => 'CM', 'en_name' => 'Cameroon', 'hr_name' => 'קמרון'),
            array('code' => 'CV', 'en_name' => 'Cape Verde', 'hr_name' => 'קייפ ורדה'),
            array('code' => 'KY', 'en_name' => 'Cayman Islands', 'hr_name' => 'איי קיימן'),
            array('code' => 'CF', 'en_name' => 'Central African Republic', 'hr_name' => 'הרפובליקה המרכז - אפריקאית'),
            array('code' => 'TD', 'en_name' => 'Chad', 'hr_name' => "צ'אד"),
            array('code' => 'CL', 'en_name' => 'Chile', 'hr_name' => "צ'ילה"),
            array('code' => 'CN', 'en_name' => 'China', 'hr_name' => 'חרסינה'),
            array('code' => 'CX', 'en_name' => 'Christmas Island', 'hr_name' => 'אי חג המולד'),
            array('code' => 'CC', 'en_name' => 'Cocos (Keeling) Islands', 'hr_name' => 'איי קוקוס'),
            array('code' => 'CO', 'en_name' => 'Colombia', 'hr_name' => 'קולומביה'),
            array('code' => 'KM', 'en_name' => 'Comoros', 'hr_name' => 'קומורו'),
            array('code' => 'CG', 'en_name' => 'Congo', 'hr_name' => 'קונגו'),
            array('code' => 'CK', 'en_name' => 'Cook Islands', 'hr_name' => 'איי קוק'),
            array('code' => 'CR', 'en_name' => 'Costa Rica', 'hr_name' => 'קוסטה ריקה'),
            array('code' => 'HR', 'en_name' => 'Croatia (Hrvatska)', 'hr_name' => 'קרואטיה'),
            array('code' => 'CU', 'en_name' => 'Cuba', 'hr_name' => 'קובה'),
            array('code' => 'CY', 'en_name' => 'Cyprus', 'hr_name' => 'קַפרִיסִין'),
            array('code' => 'CZ', 'en_name' => 'Czech Republic', 'hr_name' => "הרפובליקה הצ'כית"),
            array('code' => 'CD', 'en_name' => 'Democratic Republic of Congo', 'hr_name' => 'הרפובליקה הדמוקרטית של קונגו'),
            array('code' => 'DK', 'en_name' => 'Denmark', 'hr_name' => 'דנמרק'),
            array('code' => 'DJ', 'en_name' => 'Djibouti', 'hr_name' => "ג'יבוטי"),
            array('code' => 'DM', 'en_name' => 'Dominica', 'hr_name' => 'דומיניקה'),
            array('code' => 'DO', 'en_name' => 'Dominican Republic', 'hr_name' => 'הרפובליקה הדומיניקנית'),
            array('code' => 'TP', 'en_name' => 'East Timor', 'hr_name' => 'מזרח טימור'),
            array('code' => 'EC', 'en_name' => 'Ecudaor', 'hr_name' => 'אקוודור'),
            array('code' => 'EG', 'en_name' => 'Egypt', 'hr_name' => 'מִצְרַיִם'),
            array('code' => 'SV', 'en_name' => 'El Salvador', 'hr_name' => 'אל סלבדור'),
            array('code' => 'GQ', 'en_name' => 'Equatorial Guinea', 'hr_name' => 'גיניאה המשוונית'),
            array('code' => 'ER', 'en_name' => 'Eritrea', 'hr_name' => 'אריתריאה'),
            array('code' => 'EE', 'en_name' => 'Estonia', 'hr_name' => 'אסטוניה'),
            array('code' => 'ET', 'en_name' => 'Ethiopia', 'hr_name' => 'אֶתִיוֹפִּיָה'),
            array('code' => 'FK', 'en_name' => 'Falkland Islands (Malvinas)', 'hr_name' => 'איי מאלבינס (איי פוקלנד)'),
            array('code' => 'FO', 'en_name' => 'Faroe Islands', 'hr_name' => 'איי פרו'),
            array('code' => 'FJ', 'en_name' => 'Fiji', 'hr_name' => "פיג'י"),
            array('code' => 'FI', 'en_name' => 'Finland', 'hr_name' => 'פינלנד'),
            array('code' => 'FR', 'en_name' => 'France', 'hr_name' => 'צָרְפַת'),
            array('code' => 'FX', 'en_name' => 'France, Metropolitan', 'hr_name' => 'צרפת, מטרופוליטן'),
            array('code' => 'GF', 'en_name' => 'French Guiana', 'hr_name' => 'גיאנה הצרפתית'),
            array('code' => 'PF', 'en_name' => 'French Polynesia', 'hr_name' => 'פולינזיה הצרפתית'),
            array('code' => 'TF', 'en_name' => 'French Southern Territories', 'hr_name' => 'השטחים הדרומיים הצרפתיים'),
            array('code' => 'GA', 'en_name' => 'Gabon', 'hr_name' => 'גבון'),
            array('code' => 'GM', 'en_name' => 'Gambia', 'hr_name' => 'גמביה'),
            array('code' => 'GE', 'en_name' => 'Georgia', 'hr_name' => "ג'ורג'יה"),
            array('code' => 'DE', 'en_name' => 'Germany', 'hr_name' => 'גֶרמָנִיָה'),
            array('code' => 'GH', 'en_name' => 'Ghana', 'hr_name' => 'גאנה'),
            array('code' => 'GI', 'en_name' => 'Gibraltar', 'hr_name' => 'גיברלטר'),
            array('code' => 'GR', 'en_name' => 'Greece', 'hr_name' => 'יָוָן'),
            array('code' => 'GL', 'en_name' => 'Greenland', 'hr_name' => 'גרינלנד'),
            array('code' => 'GD', 'en_name' => 'Grenada', 'hr_name' => 'גרנדה'),
            array('code' => 'GP', 'en_name' => 'Guadeloupe', 'hr_name' => 'גוואדלופ'),
            array('code' => 'GU', 'en_name' => 'Guam', 'hr_name' => 'גואם'),
            array('code' => 'GT', 'en_name' => 'Guatemala', 'hr_name' => 'גואטמלה'),
            array('code' => 'GN', 'en_name' => 'Guinea', 'hr_name' => 'גינאה'),
            array('code' => 'GW', 'en_name' => 'Guinea-Bissau', 'hr_name' => 'גינאה ביסאו'),
            array('code' => 'GY', 'en_name' => 'Guyana', 'hr_name' => 'גיאנה'),
            array('code' => 'HT', 'en_name' => 'Haiti', 'hr_name' => 'האיטי'),
            array('code' => 'HM', 'en_name' => 'Heard and Mc Donald Islands', 'hr_name' => 'איי הרד ומקדונלד'),
            array('code' => 'HN', 'en_name' => 'Honduras', 'hr_name' => 'הונדורס'),
            array('code' => 'HK', 'en_name' => 'Hong Kong', 'hr_name' => 'הונג קונג'),
            array('code' => 'HU', 'en_name' => 'Hungary', 'hr_name' => 'הונגריה'),
            array('code' => 'IS', 'en_name' => 'Iceland', 'hr_name' => 'אִיסלַנד'),
            array('code' => 'IN', 'en_name' => 'India', 'hr_name' => 'הוֹדוּ'),
            array('code' => 'ID', 'en_name' => 'Indonesia', 'hr_name' => 'אִינדוֹנֵזִיָה'),
            array('code' => 'IR', 'en_name' => 'Iran (Islamic Republic of)', 'hr_name' => 'איראן'),
            array('code' => 'IQ', 'en_name' => 'Iraq', 'hr_name' => 'עִירַאק'),
            array('code' => 'IE', 'en_name' => 'Ireland', 'hr_name' => 'אירלנד'),
            array('code' => 'IL', 'en_name' => 'Israel', 'hr_name' => 'ישראל'),
            array('code' => 'IT', 'en_name' => 'Italy', 'hr_name' => 'אִיטַלִיָה'),
            array('code' => 'CI', 'en_name' => 'Ivory Coast', 'hr_name' => 'חוף שנהב'),
            array('code' => 'JM', 'en_name' => 'Jamaica', 'hr_name' => "ג'מייקה"),
            array('code' => 'JP', 'en_name' => 'Japan', 'hr_name' => 'יפן'),
            array('code' => 'JO', 'en_name' => 'Jordan', 'hr_name' => 'יַרדֵן'),
            array('code' => 'KZ', 'en_name' => 'Kazakhstan', 'hr_name' => 'קזחסטן'),
            array('code' => 'KE', 'en_name' => 'Kenya', 'hr_name' => 'קניה'),
            array('code' => 'KI', 'en_name' => 'Kiribati', 'hr_name' => 'קיריבטי'),
            array('code' => 'KP', 'en_name' => 'Korea, Democratic People\'s Republic of', 'hr_name' => 'קוריאה, הרפובליקה העממית הדמוקרטית של'),
            array('code' => 'KR', 'en_name' => 'Korea, Republic of', 'hr_name' => 'קוריאה'),
            array('code' => 'KW', 'en_name' => 'Kuwait', 'hr_name' => 'כווית'),
            array('code' => 'KG', 'en_name' => 'Kyrgyzstan', 'hr_name' => 'קירגיזסטן'),
            array('code' => 'LA', 'en_name' => 'Lao People\'s Democratic Republic', 'hr_name' => 'הרפובליקה הדמוקרטית העממית של לאו'),
            array('code' => 'LV', 'en_name' => 'Latvia', 'hr_name' => 'לטביה'),
            array('code' => 'LB', 'en_name' => 'Lebanon', 'hr_name' => 'לבנון'),
            array('code' => 'LS', 'en_name' => 'Lesotho', 'hr_name' => 'לסוטו'),
            array('code' => 'LR', 'en_name' => 'Liberia', 'hr_name' => 'ליבריה'),
            array('code' => 'LY', 'en_name' => 'Libyan Arab Jamahiriya', 'hr_name' => "ג'בהיריה הערבית הלובית"),
            array('code' => 'LI', 'en_name' => 'Liechtenstein', 'hr_name' => 'ליכטנשטיין'),
            array('code' => 'LT', 'en_name' => 'Lithuania', 'hr_name' => 'ליטא'),
            array('code' => 'LU', 'en_name' => 'Luxembourg', 'hr_name' => 'לוקסמבורג'),
            array('code' => 'MO', 'en_name' => 'Macau', 'hr_name' => 'מקאו'),
            array('code' => 'MK', 'en_name' => 'Macedonia', 'hr_name' => 'מוּקדוֹן'),
            array('code' => 'MG', 'en_name' => 'Madagascar', 'hr_name' => 'מדגסקר'),
            array('code' => 'MW', 'en_name' => 'Malawi', 'hr_name' => 'מלאווי'),
            array('code' => 'MY', 'en_name' => 'Malaysia', 'hr_name' => 'מלזיה'),
            array('code' => 'MV', 'en_name' => 'Maldives', 'hr_name' => 'האיים המלדיביים'),
            array('code' => 'ML', 'en_name' => 'Mali', 'hr_name' => 'מאלי'),
            array('code' => 'MT', 'en_name' => 'Malta', 'hr_name' => 'מלטה'),
            array('code' => 'MH', 'en_name' => 'Marshall Islands', 'hr_name' => 'איי מרשל'),
            array('code' => 'MQ', 'en_name' => 'Martinique', 'hr_name' => 'מרטיניק'),
            array('code' => 'MR', 'en_name' => 'Mauritania', 'hr_name' => 'מאוריטניה'),
            array('code' => 'MU', 'en_name' => 'Mauritius', 'hr_name' => 'מאוריציוס'),
            array('code' => 'TY', 'en_name' => 'Mayotte', 'hr_name' => 'מיוט'),
            array('code' => 'MX', 'en_name' => 'Mexico', 'hr_name' => 'מקסיקו'),
            array('code' => 'FM', 'en_name' => 'Micronesia, Federated States of', 'hr_name' => 'מיקרונזיה, מדינות הפדרציה של'),
            array('code' => 'MD', 'en_name' => 'Moldova, Republic of', 'hr_name' => 'מולדובה, הרפובליקה של'),
            array('code' => 'MC', 'en_name' => 'Monaco', 'hr_name' => 'מונקו'),
            array('code' => 'MN', 'en_name' => 'Mongolia', 'hr_name' => 'מונגוליה'),
            array('code' => 'MS', 'en_name' => 'Montserrat', 'hr_name' => 'מונטסראט'),
            array('code' => 'MA', 'en_name' => 'Morocco', 'hr_name' => 'מָרוֹקוֹ'),
            array('code' => 'MZ', 'en_name' => 'Mozambique', 'hr_name' => 'מוזמביק'),
            array('code' => 'MM', 'en_name' => 'Myanmar', 'hr_name' => 'מיאנמר'),
            array('code' => 'NA', 'en_name' => 'Namibia', 'hr_name' => 'נמיביה'),
            array('code' => 'NR', 'en_name' => 'Nauru', 'hr_name' => 'נאורו'),
            array('code' => 'NP', 'en_name' => 'Nepal', 'hr_name' => 'נפאל'),
            array('code' => 'NL', 'en_name' => 'Netherlands', 'hr_name' => 'הולנד'),
            array('code' => 'AN', 'en_name' => 'Netherlands Antilles', 'hr_name' => 'האנטילים ההולנדיים'),
            array('code' => 'NC', 'en_name' => 'New Caledonia', 'hr_name' => 'קלדוניה החדשה'),
            array('code' => 'NZ', 'en_name' => 'New Zealand', 'hr_name' => 'ניו זילנד'),
            array('code' => 'NI', 'en_name' => 'Nicaragua', 'hr_name' => 'ניקרגואה'),
            array('code' => 'NE', 'en_name' => 'Niger', 'hr_name' => "ניז'ר"),
            array('code' => 'NG', 'en_name' => 'Nigeria', 'hr_name' => 'ניגריה'),
            array('code' => 'NU', 'en_name' => 'Niue', 'hr_name' => 'ניואה'),
            array('code' => 'NF', 'en_name' => 'Norfork Island', 'hr_name' => 'האי נורפולק'),
            array('code' => 'MP', 'en_name' => 'Northern Mariana Islands', 'hr_name' => 'איי מריאנה הצפוניים'),
            array('code' => 'NO', 'en_name' => 'Norway', 'hr_name' => 'נורווגיה'),
            array('code' => 'OM', 'en_name' => 'Oman', 'hr_name' => 'עומאן'),
            array('code' => 'PK', 'en_name' => 'Pakistan', 'hr_name' => 'פקיסטן'),
            array('code' => 'PW', 'en_name' => 'Palau', 'hr_name' => 'פלאו'),
            array('code' => 'PA', 'en_name' => 'Panama', 'hr_name' => 'פנמה'),
            array('code' => 'PG', 'en_name' => 'Papua New Guinea', 'hr_name' => 'פפואה גינאה החדשה'),
            array('code' => 'PY', 'en_name' => 'Paraguay', 'hr_name' => 'פרגוואי'),
            array('code' => 'PE', 'en_name' => 'Peru', 'hr_name' => 'פרו'),
            array('code' => 'PH', 'en_name' => 'Philippines', 'hr_name' => 'הפיליפינים'),
            array('code' => 'PN', 'en_name' => 'Pitcairn', 'hr_name' => 'פיטקארן'),
            array('code' => 'PL', 'en_name' => 'Poland', 'hr_name' => 'פּוֹלִין'),
            array('code' => 'PT', 'en_name' => 'Portugal', 'hr_name' => 'פּוֹרטוּגָל'),
            array('code' => 'PR', 'en_name' => 'Puerto Rico', 'hr_name' => 'פוארטו ריקו'),
            array('code' => 'QA', 'en_name' => 'Qatar', 'hr_name' => 'קטאר'),
            array('code' => 'SS', 'en_name' => 'Republic of South Sudan', 'hr_name' => 'רפובליקת דרום סודאן'),
            array('code' => 'RE', 'en_name' => 'Reunion', 'hr_name' => 'איחוד'),
            array('code' => 'RO', 'en_name' => 'Romania', 'hr_name' => 'רומניה'),
            array('code' => 'RU', 'en_name' => 'Russian Federation', 'hr_name' => 'הפדרציה הרוסית'),
            array('code' => 'RW', 'en_name' => 'Rwanda', 'hr_name' => 'רואנדה'),
            array('code' => 'KN', 'en_name' => 'Saint Kitts and Nevis', 'hr_name' => 'סנט קיטס ונוויס'),
            array('code' => 'LC', 'en_name' => 'Saint Lucia', 'hr_name' => 'סנט לוסיה'),
            array('code' => 'VC', 'en_name' => 'Saint Vincent and the Grenadines', 'hr_name' => 'וינסנט הקדוש ו ה - גרנידיים'),
            array('code' => 'WS', 'en_name' => 'Samoa', 'hr_name' => 'סמואה'),
            array('code' => 'SM', 'en_name' => 'San Marino', 'hr_name' => 'סן מרינו'),
            array('code' => 'ST', 'en_name' => 'Sao Tome and Principe', 'hr_name' => 'סאו טומה ופרינסיפה'),
            array('code' => 'SA', 'en_name' => 'Saudi Arabia', 'hr_name' => 'ערב הסעודית'),
            array('code' => 'SN', 'en_name' => 'Senegal', 'hr_name' => 'סנגל'),
            array('code' => 'RS', 'en_name' => 'Serbia', 'hr_name' => 'סרביה'),
            array('code' => 'SC', 'en_name' => 'Seychelles', 'hr_name' => 'סיישל'),
            array('code' => 'SL', 'en_name' => 'Sierra Leone', 'hr_name' => 'סיירה לאונה'),
            array('code' => 'SG', 'en_name' => 'Singapore', 'hr_name' => 'סינגפור'),
            array('code' => 'SK', 'en_name' => 'Slovakia', 'hr_name' => 'סלובקיה'),
            array('code' => 'SI', 'en_name' => 'Slovenia', 'hr_name' => 'סלובניה'),
            array('code' => 'SB', 'en_name' => 'Solomon Islands', 'hr_name' => 'איי שלמה'),
            array('code' => 'SO', 'en_name' => 'Somalia', 'hr_name' => 'סומליה'),
            array('code' => 'ZA', 'en_name' => 'South Africa', 'hr_name' => 'דרום אפריקה'),
            array('code' => 'GS', 'en_name' => 'South Georgia South Sandwich Islands', 'hr_name' => "דרום ג'ורג'יה איי סנדוויץ 'דרום"),
            array('code' => 'ES', 'en_name' => 'Spain', 'hr_name' => 'סְפָרַד'),
            array('code' => 'LK', 'en_name' => 'Sri Lanka', 'hr_name' => 'סרי לנקה'),
            array('code' => 'SH', 'en_name' => 'St. Helena', 'hr_name' => 'סנט הלנה'),
            array('code' => 'PM', 'en_name' => 'St. Pierre and Miquelon', 'hr_name' => 'סנט פייר ומיקלון'),
            array('code' => 'SD', 'en_name' => 'Sudan', 'hr_name' => 'סודן'),
            array('code' => 'SR', 'en_name' => 'Suriname', 'hr_name' => 'סורינאם'),
            array('code' => 'SJ', 'en_name' => 'Svalbarn and Jan Mayen Islands', 'hr_name' => 'איי סוואלברן ויאן מאיין'),
            array('code' => 'SZ', 'en_name' => 'Swaziland', 'hr_name' => 'סווזילנד'),
            array('code' => 'SE', 'en_name' => 'Sweden', 'hr_name' => 'שבדיה'),
            array('code' => 'CH', 'en_name' => 'Switzerland', 'hr_name' => 'שוויץ'),
            array('code' => 'SY', 'en_name' => 'Syrian Arab Republic', 'hr_name' => 'הרפובליקה הערבית של סוריה'),
            array('code' => 'TW', 'en_name' => 'Taiwan', 'hr_name' => 'טייוואן'),
            array('code' => 'TJ', 'en_name' => 'Tajikistan', 'hr_name' => "טג'יקיסטן"),
            array('code' => 'TZ', 'en_name' => 'Tanzania, United Republic of', 'hr_name' => 'טנזניה, הרפובליקה המאוחדת'),
            array('code' => 'TH', 'en_name' => 'Thailand', 'hr_name' => 'תאילנד'),
            array('code' => 'TG', 'en_name' => 'Togo', 'hr_name' => 'ללכת'),
            array('code' => 'TK', 'en_name' => 'Tokelau', 'hr_name' => 'טוקלאו'),
            array('code' => 'TO', 'en_name' => 'Tonga', 'hr_name' => 'טונגה'),
            array('code' => 'TT', 'en_name' => 'Trinidad and Tobago', 'hr_name' => 'טרינידד וטובגו'),
            array('code' => 'TN', 'en_name' => 'Tunisia', 'hr_name' => 'תוניסיה'),
            array('code' => 'TR', 'en_name' => 'Turkey', 'hr_name' => 'טורקיה'),
            array('code' => 'TM', 'en_name' => 'Turkmenistan', 'hr_name' => 'טורקמניסטן'),
            array('code' => 'TC', 'en_name' => 'Turks and Caicos Islands', 'hr_name' => 'איי טורקס וקאיקוס'),
            array('code' => 'TV', 'en_name' => 'Tuvalu', 'hr_name' => 'טובאלו'),
            array('code' => 'UG', 'en_name' => 'Uganda', 'hr_name' => 'אוגנדה'),
            array('code' => 'UA', 'en_name' => 'Ukraine', 'hr_name' => 'אוקראינה'),
            array('code' => 'AE', 'en_name' => 'United Arab Emirates', 'hr_name' => 'איחוד האמירויות הערביות'),
            array('code' => 'GB', 'en_name' => 'United Kingdom', 'hr_name' => 'הממלכה המאוחדת'),
            array('code' => 'UM', 'en_name' => 'United States minor outlying islands', 'hr_name' => 'איים רחוקים של ארצות הברית'),
            array('code' => 'UY', 'en_name' => 'Uruguay', 'hr_name' => 'אורוגוואי'),
            array('code' => 'UZ', 'en_name' => 'Uzbekistan', 'hr_name' => 'אוזבקיסטן'),
            array('code' => 'VU', 'en_name' => 'Vanuatu', 'hr_name' => 'ונואטו'),
            array('code' => 'VA', 'en_name' => 'Vatican City State', 'hr_name' => 'מדינת הוותיקן'),
            array('code' => 'VE', 'en_name' => 'Venezuela', 'hr_name' => 'ונצואלה'),
            array('code' => 'VN', 'en_name' => 'Vietnam', 'hr_name' => 'וייטנאם'),
            array('code' => 'VG', 'en_name' => 'Virgin Islands (British)', 'hr_name' => 'איי הבתולה (בריטים)'),
            array('code' => 'VI', 'en_name' => 'Virgin Islands (U.S.)', 'hr_name' => 'איי הבתולה (ארה"ב)'),
            array('code' => 'WF', 'en_name' => 'Wallis and Futuna Islands', 'hr_name' => 'איי וואליס ופוטונה'),
            array('code' => 'EH', 'en_name' => 'Western Sahara', 'hr_name' => 'סהרה המערבית'),
            array('code' => 'YE', 'en_name' => 'Yemen', 'hr_name' => 'תֵימָן'),
            array('code' => 'YU', 'en_name' => 'Yugoslavia', 'hr_name' => 'יוגוסלביה'),
            array('code' => 'ZR', 'en_name' => 'Zaire', 'hr_name' => 'זאיר'),
            array('code' => 'ZM', 'en_name' => 'Zambia', 'hr_name' => 'זמביה'),
            array('code' => 'ZW', 'en_name' => 'Zimbabwe', 'hr_name' => 'זימבבואה'),
        );

        foreach ($countries as $key => $country) {
            DB::table('countries')
                ->where('en_name', $country['en_name'])
                ->update(['hr_name' => $country['hr_name'], 'code' => $country['code']]);
            //$countries[$key] = $country;
        }
    }
}