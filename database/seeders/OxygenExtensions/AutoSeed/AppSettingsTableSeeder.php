<?php
namespace Database\Seeders\OxygenExtensions\AutoSeed;

use ElegantMedia\OxygenFoundation\Database\Seeders\SeedWithoutDuplicates;
use EMedia\AppSettings\Entities\SettingGroups\SettingGroup;
use EMedia\AppSettings\Entities\Settings\Setting;
use Illuminate\Support\Str;

class AppSettingsTableSeeder extends \Illuminate\Database\Seeder
{

	use SeedWithoutDuplicates;

	public function run()
	{
		$group = SettingGroup::where('name', 'General')->first();
		if (!$group) {
			$group = SettingGroup::create([
				'name'        => 'General',
				'description' => 'General Settings',
				'sort_order'  => 10,
			]);
		}

		$data = [
			[
				'setting_key'       => 'ABOUT_US',
				'setting_data_type' => 'text',
				'description'       => 'Content for the about us screen.',
				'setting_value'     => $this->getLocalSeedData('ABOUT_US'),
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'PRIVACY_POLICY',
				'setting_data_type' => 'text',
				'description'       => 'Content for application privacy policy.',
				'setting_value'     => $this->getLocalSeedData('PRIVACY_POLICY'),
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'TERMS_AND_CONDITIONS',
				'setting_data_type' => 'text',
				'description'       => 'Content for application terms and conditions.',
				'setting_value'     => $this->getLocalSeedData('TERMS_AND_CONDITIONS'),
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'FAQ',
				'setting_data_type' => 'text',
				'description'       => 'Content for application questions and answers.',
				'setting_value'     => $this->getLocalSeedData('FAQ'),
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'WEBSITE_URL',
				'setting_data_type' => 'string',
				'setting_value'     => 'http://www.elegantmedia.com.au',
				'description'       => 'Official Website URL',
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'INSTAGRAM_URL',
				'setting_data_type' => 'string',
				'setting_value'     => 'http://www.instagram.com/' . Str::kebab(config('app.name')),
				'description'       => 'Official Instagram URL',
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'FACEBOOK_URL',
				'setting_data_type' => 'string',
				'setting_value'     => 'http://www.facebook.com/' . Str::kebab(config('app.name')),
				'description'       => 'Official Facebook URL',
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'TWITTER_URL',
				'setting_data_type' => 'string',
				'setting_value'     => 'http://www.twitter.com/' . Str::kebab(config('app.name')),
				'description'       => 'Official Twitter URL',
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
			[
				'setting_key'       => 'SNAPCHAT_URL',
				'setting_data_type' => 'string',
				'setting_value'     => 'http://www.snap.com/' . Str::kebab(config('app.name')),
				'description'       => 'Official Snapchat URL',
				'is_key_editable'   => false,
				'setting_group_id'  => $group->id,
			],
		];

		$this->seedWithoutDuplicates($data, Setting::class, 'setting_key', 'setting_key');
	}

	/**
	 *
	 * Fetch seed data from a local file
	 *
	 * @param $seedKey
	 * @param null $filename
	 * @return false|string
	 */
	protected function getLocalSeedData($seedKey, $filename = null)
	{
		// by default, look for a .txt file with the same name
		if (empty($filename)) {
			$filename = Str::snake(strtolower($seedKey)) . '.txt';
		}

		$relPath = 'seeders' . DIRECTORY_SEPARATOR . 'SeedData' . DIRECTORY_SEPARATOR . $filename;
		if (file_exists(database_path($relPath))) {
			$template = file_get_contents(database_path($relPath));

			$content = str_replace([
				'[[APP_NAME]]',
			], [
				config('app.name'),
			], $template);

			return $content;
		}

		return "~ADD YOUR {$seedKey} CONTENT. Or create a file at {$relPath} to auto-seed.~";
	}

}
