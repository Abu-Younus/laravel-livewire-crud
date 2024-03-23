<div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center">Student CRUD</h3>
                <hr class="divider"/>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-12">
                            <h5 class="card-title float-start">Student Data</h5>
                            <a href="#" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#studentModal">Add New</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="width: 100% !important; overflow-x: scroll;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>SL NO.</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>ID NO.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $key=>$student)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td><img src="{{ asset('assets/images/student') }}/{{ $student->image }}" width="40" height="40"/></td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->identity }}</td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#studentModal" wire:click.prevent="editStudent({{ $student->id }})">Edit</a>
                                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#studentDeleteModal" wire:click.prevent="deleteStudent({{ $student->id }})">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="studentModalLabel">@if($edit==true) Student Update @else Student Add @endif</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click.prevent="closeStudentModal"></button>
            </div>
            <div class="modal-body">
                <form @if($edit==true) wire:submit.prevent="updateStudent" @else wire:submit.prevent="storeStudent" @endif>
                    <div class="mb-3">
                      <label for="input-form" class="form-label">Full Name</label>
                      <input type="text" class="form-control" id="input-form" wire:model="name">
                      @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="input-form" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="input-form" wire:model="email">
                        @error('email') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="input-form" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="input-form" wire:model="phone">
                        @error('phone') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="input-form" class="form-label">ID Number</label>
                        <input type="text" class="form-control" id="input-form" wire:model="identity">
                        @error('identity') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>
                    @if ($edit==true)
                        <div class="mb-3">
                            <div class="row">
                                <label for="input-form" class="form-label">Image</label>
                                <div class="col-md-10">
                                    <input type="file" class="form-control" id="input-form" wire:model="newimage">
                                </div>
                                <div class="col-md-2">
                                    @if ($newimage)
                                        <img src="{{ $newimage->temporaryUrl() }}" width="40" />
                                    @else
                                        @if ($image)
                                            <img src="{{ asset('assets/images/student') }}/{{ $image }}" width="40" />
                                        @endif
                                    @endif
                                </div>
                                @error('newimage') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="row">
                                <label for="input-form" class="form-label">Image</label>
                                <div class="col-md-10">
                                    <input type="file" class="form-control" id="input-form" wire:model="image">
                                </div>
                                <div class="col-md-2">
                                    @if ($image)
                                        <img src="{{ $image->temporaryUrl() }}" width="40" />
                                    @endif
                                </div>
                                @error('image') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">@if($edit==true) Update @else Submit @endif</button>
                </form>
            </div>
        </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="studentDeleteModal" tabindex="-1" aria-labelledby="studentDeleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="studentDeleteModalLabel">Student Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Are you sure? you want to delete this student!</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" wire:click.prevent="destroyStudent">Yes, delete it!</button>
            </div>
        </div>
        </div>
    </div>
</div>
