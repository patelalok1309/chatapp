<x-app-layout>

    <div class="container mt-4">

        <div class="row">

            @if (count($users) > 0)

                {{-- Chat sidebar section  --}}
                <div class="col-sm-3">
                    <ul class="list-group chat-list-ul">
                        @foreach ($users as $user)
                            <li class="list-group-item chat-item" id="{{ $user->id }}">
                                <img src="{{ $user->image ? $user->image : asset('images/dummy.avif') }}"
                                    alt="user avatar" class="user-image">
                                <div class="chat-user-info">
                                    <p class="capitalize text-dark fs-6 chat-username">
                                        {{ substr($user->name, 0, 10) }}...</p>
                                    <b><sup id="{{ $user->id }}-status" class="offline-status">offline</sup></b>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Chat area section  --}}
                <div class="col-sm-9">

                    <h3 class="start-head">Click to start the chat</h3>
                    <div class="chat-section">

                        {{-- User Profile Topbar  --}}
                        <div class="gap-2 px-2 pb-1 chat-profile d-flex justify-content-start align-items-center">
                            <img src="{{ asset('images/dummy.avif') }}" alt="user profile" class="user-image"
                                id="user-profile" height="2rem" width="2rem">
                            <div class="chat-profile-info text-light-emphasis fs-4">
                                <p id="user-name"></p>
                            </div>
                        </div>


                        <div id="chat-container">
                            <div class="shadow-sm current-user-chat">
                                <span class="current-user-chat-wrapper">Hy</span>
                            </div>
                            <div class="distance-user-chat">
                                <span class="distance-user-chat-wrapper">Hello..</span>
                            </div>
                        </div>

                        <div class="chat-form-inputs">
                            <form action="" id="chat-form" class="">
                                <div class="flex gap-1 mt-2 justify-content-end">
                                    <input type="text" name="message" id="message" placeholder="Enter message...."
                                        required class="text-sm rounded w-50 form-input msg-input">
                                    <input type="submit" value="send" class="btn btn-success ">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <h6>Users not found!</h6>
                </div>
            @endif
        </div>
    </div>

    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade" id="deleteChatModal" tabindex="-1" aria-labelledby="deleteChatModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteChatModal">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="" id="delete-chat-form">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="delete-chat-id">
                        <p>Are you sure you want to delete message permanently ?</p>
                        <p><b id="delete-message"></b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
