<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use ElegantMedia\SimpleRepository\Search\Filterable;
use EMedia\AppSettings\Http\Controllers\Manage\ManageSettingsController;

class SettingsController extends ManageSettingsController
{
    // only these settings will display in index view
    private array $visibleSettings = ['PRIVACY_POLICY', 'ABOUT_US', 'TERMS_AND_CONDITIONS', 'FAQ'];

    protected function getIndexFilter(): Filterable
    {
        $filter = $this->repo->newSearchFilter(true);

        $filter
            ->whereIn('setting_key', $this->visibleSettings)
            ->with(['group']);

        return $filter;
    }

    public function index()
    {

        $data = [
            'pageTitle'                 => $this->getResourcePluralName(),
            'allItems'                  => $this->repo->search($this->getIndexFilter()),
            'isDestroyingEntityAllowed' => $this->isDestroyAllowed(),
            'canCreateEntities'         => $this->canCreateEntities(),
            'canEditEntities'           => $this->canEditEntities(),
        ];

        return view('manage.settings.index', $data);
    }

    /**
     *
     * Edit the resource
     *
     * @param $id
     *
     * @return Factory|View
     * @throws FileNotFoundException
     */
    public function edit($id)
    {
        $entity = $this->repo->find($id);

        $data = [
            'pageTitle' => $this->getEditPageTitle($entity),
            'entity'    => $entity,
            'form'      => $this->getEditForm($entity),
            'key'       => $entity->setting_key,
        ];

        $viewName = $this->getEditViewName();

        switch ($entity->setting_key) {
            // if setting key is PRIVACY_POLICY, show the privacy policy pdf upload view
            // if setting key is TERMS_AND_CONDITIONS, show the pdf upload view
            case 'TERMS_AND_CONDITIONS':
            case 'PRIVACY_POLICY':
            case 'FAQ':
            case 'ABOUT_US':
                return view('manage.settings.form', $data);
            default:
                return view($viewName, $data);
        }
    }

    /**
     * Update about us page values
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'terms_and_conditions' => 'nullable',
            'privacy_policy'       => 'nullable',
            'about_us'             => 'nullable',
            'faq'                  => 'nullable',
            'facebook_url'         => 'nullable|max:255',
            'instagram_url'        => 'nullable|max:255',
            'support_email'        => 'nullable|email',
            'support_phone'        => 'nullable|max:255|min:9',
        ]);

        $data = $request->all([
            'terms_and_conditions',
            'privacy_policy',
            'about_us',
            'faq',
            'facebook_url',
            'instagram_url',
            'support_email',
            'support_phone',
        ]);

        try {
            foreach ($data as $key => $value) {
                if ($value) {
                    setting_set(strtoupper($key), $value);
                }
            }
            return back()->with('success', 'Settings Updated');
        } catch (\Throwable $exception) {
            return back()->with('error', 'Failed to update settings');
        }
    }
}