<?php

namespace App\Entities\Personalities;

use App\Entities\BaseRepository;
use App\Entities\Filters\Filter;
use App\Entities\ProfilePreferences\ProfilePreference;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PersonalitiesRepository extends BaseRepository
{
    public function __construct(Personality $model)
    {
        parent::__construct($model);
    }

	public function matchFilter($request)
	{
		$preference = ProfilePreference::where('user_id', Auth::user()->id)->pluck('preferred_user_id');

		$profiles = User::where('id', '!=', Auth::user()->id)
			->where('profile_setup_step', User::LOGGED_IN_PAGE)
			->whereNotIn('id', $preference)
			// ->when($request->filled('dating_preference'), function ($query) use ($request)
			// {
			// 	if ($request->dating_preference != "All")
			// 		$query->whereHas('personality', fn($q) => $q->where('dating_intentions', $request->dating_preference))
			// 			->with(['personality' => fn($q) => $q->where('dating_intentions', $request->dating_preference)]);
			// })
			->when($request->filled('relationship_type'), function ($query) use ($request)
			{
				$query->whereHas('personality', fn($q) => $q->where('relationship_type', $request->relationship_type))
					->with(['personality' => fn($q) => $q->where('relationship_type', $request->relationship_type)]);
			})
			->when($request->filled('dating_preference'), function ($query) use ($request)
			{
				if ($request->dating_preference != "All")
					$query->where('gender', $request->dating_preference);
			})
			->when($request->filled('like_to_have_children'), function ($query) use ($request)
			{
				$query->whereHas('personality', fn($q) => $q->where('like_to_have_more_childran', $request->like_to_have_children))
					->with(['personality' => fn($q) => $q->where('like_to_have_more_childran', $request->like_to_have_children)]);
			})
			->when(
				$request->filled('min_age') && $request->filled('max_age') && $request->max_age > 20,
				fn($query) => $query->whereBetween('age', [$request->min_age, $request->max_age])
			)
			->get();
			$filter = Filter::updateOrCreate(
				['user_id' => Auth::user()->id], // Search criteria
				[
					'relationship_type' => $request->relationship_type,
					'preferences' => $request->dating_preference,
					'children' => $request->like_to_have_children,
					'min_age' => $request->min_age,
					'max_age' => $request->max_age
				]
			);
		return $profiles;
	}
}
