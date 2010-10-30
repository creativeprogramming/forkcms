<?php
/**
 *
 * This file should be generated by Fork CMS, it contains
 * more information about the navigation in the backend. Do NOT edit.
 *
 * REMARK: do NOT delete this file
 *
 * @author		Fork CMS
 * @generated	not
 */

$navigation[0] = array('url' => 'dashboard/index', 'label' => 'Dashboard');
$navigation[1] = array('url' => 'pages/index', 'label' => 'Pages', 'selected_for' => array('pages/add', 'pages/edit'));
$navigation[2] = array('url' => 'content_blocks/index', 'label' => 'Modules');
$navigation[2]['children'][0] = array('url' => 'content_blocks/index', 'label' => 'ContentBlocks', 'selected_for' => array('content_blocks/add', 'content_blocks/edit'));
$navigation[2]['children'][1] = array('url' => 'tags/index', 'label' => 'Tags', 'selected_for' => array('tags/edit'));
$navigation[2]['children'][2] = array('url' => 'blog/index', 'label' => 'Blog');
$navigation[2]['children'][2]['children'][0] = array('url' => 'blog/index', 'label' => 'Articles', 'selected_for' => array('blog/add', 'blog/edit', 'blog/import_blogger'));
$navigation[2]['children'][2]['children'][1] = array('url' => 'blog/comments', 'label' => 'Comments', 'selected_for' => array('blog/edit_comment'));
$navigation[2]['children'][2]['children'][2] = array('url' => 'blog/categories', 'label' => 'Categories', 'selected_for' => array('blog/add_category', 'blog/edit_category'));
$navigation[2]['children'][3] = array('url' => 'search/statistics', 'label' => 'Search');
$navigation[2]['children'][3]['children'][0] = array('url' => 'search/statistics', 'label' => 'Statistics');
$navigation[2]['children'][3]['children'][1] = array('url' => 'search/synonyms', 'label' => 'Synonyms', 'selected_for' => array('search/add_synonym', 'search/edit_synonym'));
$navigation[3] = array('url' => 'analytics/index', 'label' => 'Marketing');
$navigation[3]['children'][0] = array('url' => 'analytics/index', 'label' => 'Analytics', 'selected_for' => 'analytics/loading');
$navigation[3]['children'][0]['children'][0] = array('url' => 'analytics/content', 'label' => 'Content');
$navigation[3]['children'][0]['children'][1] = array('url' => 'analytics/all_pages', 'label' => 'AllPages');
$navigation[3]['children'][0]['children'][2] = array('url' => 'analytics/exit_pages', 'label' => 'ExitPages');
$navigation[3]['children'][0]['children'][3] = array('url' => 'analytics/landing_pages', 'label' => 'LandingPages', 'selected_for' => array('analytics/add_landing_page', 'analytics/edit_landing_page', 'analytics/detail_page'));
$navigation[4] = array('url' => 'mailmotor/index', 'label' => 'Mailmotor');
$navigation[4]['children'][0] = array('url' => 'mailmotor/index', 'label' => 'Newsletters', 'selected_for' => array('mailmotor/add', 'mailmotor/edit', 'mailmotor/edit_mailing_campaign', 'mailmotor/statistics', 'mailmotor/statistics_link', 'statistics_bounces', 'statistics_campaign', 'statistics_opens', 'statistics_unopens', 'reset'));
$navigation[4]['children'][1] = array('url' => 'mailmotor/campaigns', 'label' => 'Campaigns', 'selected_for' => array('mailmotor/add_campaign', 'mailmotor/edit_campaign', 'mailmotor/statistics_campaign'));
$navigation[4]['children'][2] = array('url' => 'mailmotor/groups', 'label' => 'MailmotorGroups', 'selected_for' => array('mailmotor/add_group', 'mailmotor/edit_group', 'mailmotor/custom_fields', 'mailmotor/add_custom_field', 'mailmotor/edit_custom_field'));
$navigation[4]['children'][3] = array('url' => 'mailmotor/addresses', 'label' => 'Addresses', 'selected_for' => array('mailmotor/add_address', 'mailmotor/edit_address', 'mailmotor/import_addresses'));
$navigation[5] = array('url' => 'settings/index', 'label' => 'Settings');
$navigation[5]['children'][0] = array('url' => 'settings/index', 'label' => 'General');
$navigation[5]['children'][1] = array('url' => 'settings/email', 'label' => 'Advanced');
$navigation[5]['children'][1]['children'][0] = array('url' => 'settings/email', 'label' => 'Email');
$navigation[5]['children'][2] = array('url' => 'users/index', 'label' => 'Users', 'selected_for' => array('users/add', 'users/edit'));
$navigation[5]['children'][3] = array('url' => 'settings/themes', 'label' => 'Themes');
$navigation[5]['children'][3]['children'][0] = array('url' => 'settings/themes', 'label' => 'ThemesSelection');
$navigation[5]['children'][3]['children'][1] = array('url' => 'pages/templates', 'label' => 'Templates', 'selected_for' => array('pages/add_template', 'pages/edit_template'));
$navigation[5]['children'][4] = array('url' => 'locale/index', 'label' => 'Translations', 'selected_for' => array('locale/add', 'locale/edit', 'locale/analyse'));
$navigation[5]['children'][5] = array('url' => 'analytics/settings', 'label' => 'Modules');
$navigation[5]['children'][5]['children'][0] = array('url' => 'analytics/settings', 'label' => 'Analytics');
$navigation[5]['children'][5]['children'][1] = array('url' => 'blog/settings', 'label' => 'Blog');
$navigation[5]['children'][5]['children'][2] = array('url' => 'search/settings', 'label' => 'Search');
$navigation[5]['children'][5]['children'][3] = array('url' => 'pages/settings', 'label' => 'Pages');
$navigation[5]['children'][5]['children'][4] = array('url' => 'mailmotor/settings', 'label' => 'Mailmotor');

?>