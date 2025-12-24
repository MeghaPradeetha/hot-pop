@section('pageMainActions')
    <form action="">
        <div class="row">
            <div class="col-md-6">
                @parent
            </div>

            <div class="col-md-3">
                <select class="form-control" id="filter-usertype" name="status">
                    <option value="">All Users</option>
                    <option {{ app('request')->input('status') == '6' ? 'selected' : '' }} value="6">
                        Not Verified
                    </option>
                    <option {{ app('request')->input('status') == '1' ? 'selected' : '' }} value="1">
                        Email Verified
                    </option>
                    <option {{ app('request')->input('status') == '2' ? 'selected' : '' }} value="2">
                        Basic Info Completed
                    </option>
                    <option {{ app('request')->input('status') == '3' ? 'selected' : '' }} value="3">
                        Intro Video Uploaded
                    </option>
                    <option {{ app('request')->input('status') == '4' ? 'selected' : '' }} value="4">
                        Images Uploaded
                    </option>
                    <option {{ app('request')->input('status') == '5' ? 'selected' : '' }} value="5">
                        Profile Completed
                    </option>
                </select>
            </div>
            <div class="col-md-3 input-fields--medium">
                <div class="input-group">
                    <input autocomplete="off" class="form-control" name="q" placeholder="Search" type="text" value="{{ request('q') }}">
                    <span class="input-group-append">
                        <button class="btn btn-success" type="submit">Search</button>
                    </span>
                </div>
            </div>
        </div>
    </form>
@stop
