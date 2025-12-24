<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <h3 class="p-b-50">Log Out</h3>
                    <p class="f-16 d5 p-b-50">Are you sure you want to logout</p>
                    <div class="d-lg-flex d-block justify-content-between">
                        <div>
                            <button type="button" class="small-button" data-bs-dismiss="modal">No</button>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="sumbit" class="small-button color">Yes</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
