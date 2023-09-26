<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Add General Contact settings
        DB::table('settings')->insert([
            [
                'key' => 'contact.company_name',
                'value' => 'Brainshift AI',
            ], [
                'key' => 'contact.company_group',
                'value' => 'Brainshift Group',
            ], [
                'key' => 'contact.address1',
                'value' => 'Nottingham',
            ], [
                'key' => 'contact.city',
                'value' => 'Nottinghamshire',
            ], [
                'key' => 'contact.country',
                'value' => 'England',
            ], [
                'key' => 'contact.postcode',
                'value' => 'NG1 1LD',
            ], [
                'key' => 'contact.vat_number',
                'value' => '',
            ], [
                'key' => 'contact.phone',
                'value' => '',
            ], [
                'key' => 'contact.footer_text',
                'value' => 'Get in touch with us',
            ], [
                'key' => 'contact.contact_form_url',
                'value' => 'https://www.brainshift.ai/en-gb/contact/',
            ], [
                'key' => 'shop.url',
                'value' => 'https://www.brainshift.ai/en-gb/corrugated-sheets-products/',
            ], [
                'key' => 'email.hubspot_client_id',
                'value' => '',
            ], [
                'key' => 'email.hubspot_client_secret',
                'value' => '',
            ], [
                'key' => 'email.installer_request_id',
                'value' => 'd-aa1f4de814b54703a33485f14f5929e8',
            ], [
                'key' => 'email.case_study_update_id',
                'value' => 'd-6b19d14ceaee4abd8e9d6c6bfee8990e',
            ], [
                'key' => 'email.farmer_shed_solution_id',
                'value' => 'd-cf30772d1db84d5cb86c7855779f75df',
            ], [
                'key' => 'email.user_edit_request_id',
                'value' => 'd-d73ea0abddc149d0adda39927d7458f0',
            ], [
                'key' => 'email.user_delete_id',
                'value' => 'd-ad6a01b973d84589819bd2bcec07b0ba',
            ],
        ]);

        $termsAndConditions =
            '
 <h2 dir="ltr"><span>Terms and Conditions</span></h2>
    <p dir="ltr" style="color: red;"><span>17th November 2022 *PLEASE NOTE THESE ARE HOLDING T&amp;C&rsquo;s*</span></p>
    <p><span><span>&nbsp;</span></span></p>
    <p dir="ltr"><span>This is the website of [Your Company Name] (Company Number [Your Company Number]) (a company registered in [Your Country], whose registered office [and the address for communications relating to this website] is at [Your Registered Office Address].</span></p>
    <p dir="ltr"><span>The content and information provided by [Your Company Name] in this website (&ldquo;the Content&rdquo;) is for general guidance only. Whilst care has been taken in preparing the Content, [Your Company Name] does not warrant its accuracy and shall not be liable for any errors, omissions, or inaccuracies in the Content. If you wish to rely on the Content, you should independently verify it.</span></p>
    <p dir="ltr"><span>[Your Company Name] authorizes you to view or download a single copy of the material on the website to a personal computer [solely for your own personal use or for non-commercial research purposes] [provided you include the following copyright notice:</span></p>
    <p dir="ltr"><span>&ldquo;Copyright [Year], [Your Company Name]. All rights reserved&rdquo;. Any special rules for the use of certain software and other items provided on the website may be included in specific areas or on a specific page of the website and are incorporated into these Terms and Conditions by reference].</span></p>
    <p dir="ltr"><span>The Content and design of this website are protected by copyright (and/or other intellectual property rights) either owned by [Your Company Name] or used under license from third-party copyright owners. Any use of the Content not expressly permitted by these Terms and Conditions may infringe such intellectual property rights. If you breach any of these Terms and Conditions, your permission to use the Content automatically terminates, and you must immediately destroy any copies you have made of any part of the Content. [Your Company Name] reserves its right to take such action as it thinks fit in these circumstances.</span></p>
    <p dir="ltr"><span>Other than as specified above, you will not, nor will you allow any other person to:-</span></p>
    <p dir="ltr"><span>1. reproduce, publish, transmit, circulate, distribute, download, alter, translate, add to, delete, remove, or tamper with the Content, this website, or any part of it; or</span></p>
    <p dir="ltr"><span>2. directly or indirectly attempt to distribute or interfere with or alter (in whole or part) the Content or this website.</span></p>
    <p dir="ltr"><span>You may find this website is linked to other websites. [Your Company Name] is not responsible for, and neither does it endorse, the content or accuracy of such linked websites. Your use of such linked websites is at your own risk and is subject to the terms and conditions of use for those linked websites.</span></p>
    <p dir="ltr"><span>Any person who wishes to provide a link to this website must seek prior written permission from [Your Company Name].</span></p>
    <p dir="ltr"><span>[Trade marks used in this website are trade marks of [Your Company Name] its subsidiaries or third-party licensors.]</span></p>
    <p dir="ltr"><span>This website operates subject to the laws of [Your Country]. The Courts of [Your Country] shall have exclusive jurisdiction to settle any disputes, which may arise out of the use of this website. To this end, you irrevocably agree to submit to the jurisdiction of the Courts of [Your Country] and irrevocably waive any objection to any action or proceedings being brought in such Courts or any claim that any such action or proceedings have been brought in any inconvenient form. However, [Your Company Name] shall still be entitled to take proceedings against users abroad and/or to enforce judgments or orders against them there.</span></p>
    <p dir="ltr"><span>[Your Company Name] accepts no responsibility and has no liability in the event that the Content and/or the website does not comply with the laws of a jurisdiction outside [Your Country].</span></p>
    <p dir="ltr"><span>THIS WEBSITE IS MADE AVAILABLE FOR PUBLIC VIEWING ON THE BASIS THAT [Your Company Name] EXCLUDES ALL LIABILITY WHATSOEVER FOR ANY LOSS OR DAMAGE HOWSOEVER ARISING OUT OF THE USE OF THIS WEBSITE OR RELIANCE UPON THE CONTENT PROVIDED IN THIS WEBSITE. THE LIMITATIONS SET OUT IN THIS NOTICE APPLY IN CASES OF BREACH OF CONTRACT, NEGLIGENCE, TORT, OR OTHERWISE. THEY SHALL APPLY TO THE FULLEST EXTENT PERMITTED BY LAW</span></p>
';

        DB::table('settings')->insert([
            [
                'key' => 'static_page.terms-and-conditions',
                'value' => sprintf($termsAndConditions, 'Terms and Conditions'),
            ],
        ]);

        $privacyPolicy =
            '
<h2 dir="ltr"><span>Privacy Policy</span></h2>
<p dir="ltr" style="color: red"><span>17th November 2022 *PLEASE NOTE THIS IS A HOLDING PRIVACY POLICY*</span></p>
<h2 dir="ltr"><span><span>&nbsp;</span></span></h2>
<p dir="ltr"><span>Privacy Policy</span></p>
<p dir="ltr"><span>Privacy Notice</span></p>
<p dir="ltr"><span>(A) This Notice</span></p>
<p dir="ltr"><span>Summary: This notice explains how we may Process your Personal Data. This Notice may be amended or updated from time to time, so please check it regularly for updates.</span></p>
';

        DB::table('settings')->insert([
            [
                'key' => 'static_page.privacy-policy',
                'value' => sprintf($privacyPolicy, 'Privacy Policy'),
            ],
        ]);

        $cookiePolicy =
            '';
        DB::table('settings')->insert([
            [
                'key' => 'static_page.cookie-policy',
                'value' => sprintf($cookiePolicy, 'Cookie Policy'),
            ],
        ]);
    }
}
