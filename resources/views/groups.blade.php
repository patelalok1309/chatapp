<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-2 fs-4 text-light">Groups</h1>

        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createGroupModal">create
            group</button>

        <table class="table text-light table-sm">
            <thead>
                <tr>
                    <th>Sr.no</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Limit</th>
                    <th>Members</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @if (count($groups) > 0)
                    @foreach ($groups as $group)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img src="{{ $group->image }}" alt="image" style="width: 100px; height:100px;"></td>
                            <td>{{ $group->name }}</td>
                            <td>{{ $group->join_limit }}</td>
                            <td><a class="cursor-pointer allMember link-opacity-100-hover" data-limit="{{ $group->join_limit }}"
                                    data-id="{{ $group->id }}" data-bs-toggle="modal" data-bs-target="#memberModal">
                                    members</a></td>
                            <td></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th colspan="6">No Groups Found!</th>
                    </tr>
                @endif
            </tbody>
        </table>


        {{-- Create Group Modal  --}}
        <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModal"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createGroupModalTitle">Create New Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="createGroupForm" enctype="multipart/form-data">
                        <div class="modal-body">

                            <div class='my-2 form-group'>
                                <label for='name' class='text-sm form-label '>Group Name</label>
                                <input type='text' name='name' id='name' class='rounded form-control'
                                    required placeholder='Enter Group Name' />
                            </div>


                            <div class='my-2 form-group'>
                                <label for='join_limit' class='text-sm form-label '>Limit</label>
                                <input type='number' name='join_limit' id='join_limit' class='rounded form-control'
                                    placeholder='Enter users limit' />
                            </div>

                            <div class='my-2 form-group'>
                                <label for='image' class='text-sm form-label '>Profile</label><br>
                                <input type='file' name='image' id='image' class='form-control-file' />
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Members Modal  --}}
        <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModal"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="memberModal Title">Create New Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="add-member-forn" enctype="multipart/form-data">

                        <input type="hidden" name="group_id" id="add-group-id">
                        <input type="hidden" name="limit" id="add-limit">

                        <div class="modal-body">

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <div class="addMembersTable">
                                                <table class="addMembersInTable">
                                                    
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <span class="text-xs add-member-error text-danger"></span>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
