@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-3 p-lg-4 ">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2 text-white">Matching Filters</h3>
                    <a href="{{ route('home-profile') }}"><i class="fa-solid fa-arrow-left circle-back text-white" style="border-color: #fff;"></i></a>
                </div>

                <form action="{{ route('home-profile') }}" class="p-200" method="POST">
                    @csrf
                    @method('GET')
					{{-- {{ dd($request->all()); }} --}}
                    {{-- <div class="p-b-24">
                        <p class="form-label fw-bold">Gender</p>
                        <select class="form-select" name="gender">
                            <option value="">Select your gender preference</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-binary">Non Binary</option>
                            <option value="All">All</option>
                        </select>
                        @if ($errors->has('gender'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div> --}}
                    <input hidden name="filter" value="true">
                    <div class="p-b-24">
                        <p class="form-label fw-bold">Relationship Type</p>
                        <select class="form-select" name="relationship_type">
							@if ($filter != null && $filter->relationship_type)
							<option value="{{ $filter->relationship_type }}">{{ $filter->relationship_type }}</option>
							@endif
                            <option value="">Select your prefered relationship type</option>
                            <option value="Relationship">Relationship</option>
                            <option value="Keeping_it_casual">Keeping it casual</option>
                            <option value="Friendship">Friendship</option>
                            <option value="Figuring_out_my_relationship_goals">Figuring out my relationship goals</option>
                        </select>
                        @if ($errors->has('relationship_type'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('relationship_type') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="p-b-24">
                        <p class="form-label fw-bold">Dating Preferences</p>
                        <select class="form-select" name="dating_preference">
							@if ($filter != null && $filter->preferences)
							<option value="{{ $filter->preferences }}">{{ $filter->preferences }}</option>
							@endif
                            <option value="">Select your dating preference</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-binary">Non Binary</option>
                            <option value="All">All</option>
                        </select>
                        @if ($errors->has('dating_preference'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('dating_preference') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="p-b-24">
                        <p class="form-label fw-bold">Would you like to have more children in the future ?</p>
                        <select class="form-select" name="like_to_have_children">
							@if ($filter != null && $filter->children)
							<option value="{{ $filter->children }}">{{ $filter->children }}</option>
							@endif
                            <option value="">Select preference</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @if ($errors->has('like_to_have_children'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('like_to_have_children') }}</strong>
                            </span>
                        @endif
                    </div>
                    {{-- <div class="p-b-24">
						<p class="form-label fw-bold">Distance (Km)</p>
						<div class="range-selector">
							<section class="range-slider container">
								<span class="output outputOne1"></span>
								<span class="output outputTwo1"></span>
								<span class="full-range"></span>
								<span class="incl-range1"></span>
								<input name="rangeOne1"    min="20" max="40" step="1" type="range">
								<input name="rangeTwo1"  min="20" max="40" step="1" type="range">
							</section>
						</div>
					</div> --}}
                    @include('pages.partials.age-range')

                    <div class="d-lg-flex d-block justify-content-between p-t-30">
                        <input class="small-button" type="reset" value='Clear All' />
                        <input class="small-button color" type="submit" value="Filter Results">
                    </div>

                </form>

            </div>
        </div>
    </section>
@endsection
