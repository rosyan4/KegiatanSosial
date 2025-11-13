{{-- Modal untuk melihat detail setting --}}
<div class="modal fade" id="settingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingModalTitle">Setting Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Key:</th>
                                <td id="modal-setting-key"></td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td id="modal-setting-name"></td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td id="modal-setting-type"></td>
                            </tr>
                            <tr>
                                <th>Group:</th>
                                <td id="modal-setting-group"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Current Value:</th>
                                <td id="modal-setting-value"></td>
                            </tr>
                            <tr>
                                <th>Required:</th>
                                <td id="modal-setting-required"></td>
                            </tr>
                            <tr>
                                <th>Order:</th>
                                <td id="modal-setting-order"></td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td id="modal-setting-updated"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p id="modal-setting-description" class="text-muted"></p>
                </div>

                @if(isset($setting) && $setting->hasOptions())
                <div class="mt-3">
                    <h6>Available Options:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Value</th>
                                    <th>Label</th>
                                </tr>
                            </thead>
                            <tbody id="modal-setting-options">
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editSetting()">
                    <i class="fas fa-edit me-1"></i> Edit Setting
                </button>
            </div>
        </div>
    </div>
</div>