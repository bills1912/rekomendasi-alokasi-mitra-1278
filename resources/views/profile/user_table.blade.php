@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h4 class="mt-4 mb-3">Daftar Pengguna</h4>
                <div class="row justify-content-center alokasi-honor-mitra">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <span class="mt-3">Daftar Pengguna dari Aplikasi Seemitra</span>
                            </div>
                            <div class="card-body">
                                <table id="pengguna-all"
                                    class="table table-striped table-bordered display dtr-inline collapsed"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nama Pengguna</th>
                                            <th>Email</th>
                                            <th>Status Subject Matter</th>
                                            <th>Status Admin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_of_user as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->is_sm ? 'Subject Matter' : 'Anggota' }}</td>
                                                <td>{{ $user->is_admin ? 'Admin' : 'Pengguna Umum' }}</td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm editDataMitraSurveiTeralokasi"
                                                        style="position:inline" data-bs-toggle="modal"
                                                        data-bs-target="#editPengguna{{ $user->id }}"><i
                                                            class="fa-regular fa-pen-to-square"></i>
                                                    </button>
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
    </div>
    @foreach ($list_of_user as $user)
        <div class="modal fade" id="editPengguna{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mb-3">
                        <h5 class="modal-title col-sm-10" id="exampleModalLabel">Form Penetapan SBML Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/editDataPengguna', $user->id) }}" method="POST">
                            @csrf
                            <div class="form-group flex-group">
                                <label class="col-sm-4 required" for="namaPenggunaSeemitra">Nama Pengguna:</label>
                                <input class="form-control" type="text" name="namaPenggunaSeemitra"
                                    id="namaPenggunaSeemitra" value="{{ $user->name }}">
                            </div>
                            <div class="form-group flex-group">
                                <label class="col-sm-4 required" for="emailPenggunaSeemitra">Email:</label>
                                <input class="form-control" type="text" name="emailPenggunaSeemitra"
                                    id="emailPenggunaSeemitra" value="{{ $user->email }}">
                            </div>
                            <div class="form-group flex-group">
                                <label class="col-sm-4 required" for="statusSubjectMatter">Status Subject Matter:</label>
                                <select class="form-select" name="statusSubjectMatter" id="statusSubjectMatter"></select>
                            </div>
                            <div class="form-group flex-group">
                                <label class="col-sm-4 required" for="statusAdmin">Status Admin:</label>
                                <select class="form-select" name="statusAdmin" id="statusAdmin"></select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa-solid fa-floppy-disk pr-1"></i>Ubah Data Pengguna</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @push('userStatusSelect2')
            <script>
                $('body').on('shown.bs.modal', '#editPengguna{{ $user->id }}', function() {
                    $(this).find('select[name="statusSubjectMatter"]').each(function() {
                        $(this).select2({
                            theme: 'bootstrap-5',
                            allowClear: true,
                            data: {
                                id: '{{ $user->is_sm }}',
                                text: '{{ $user->is_sm ? "Subject Matter" : "Anggota" }}'
                            },
                            placeholder: 'Pilih Status Subject Matter',
                            dropdownParent: $('#editPengguna{{ $user->id }}'),
                            ajax: {
                                url: "statusSubjectMatter",
                                processResults: function({
                                    data
                                }) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                id: item.jenis_sm,
                                                text: item.jenis_sm ? "Subject Matter" :
                                                    "Anggota"
                                            }
                                        })
                                    }
                                }
                            }
                        }).trigger('change');
                        $(this).val([{id: '{{ $user->is_sm }}', text: '{{ $user->is_sm ? "Subject Matter" : "Anggota" }}'}]).trigger('change');
                    });
                });

                $('body').on('shown.bs.modal', '#editPengguna{{ $user->id }}', function() {
                    $(this).find('select[name="statusAdmin"]').each(function() {
                        $(this).select2({
                            theme: 'bootstrap-5',
                            placeholder: 'Pilih Status Admin',
                            dropdownParent: $('#editPengguna{{ $user->id }}'),
                            ajax: {
                                url: "statusAdmin",
                                processResults: function({
                                    data
                                }) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                id: item.status_admin,
                                                text: item.status_admin ? "Admin" :
                                                    "Pengguna Umum"
                                            }
                                        })
                                    }
                                }
                            }
                        });
                    });
                });
            </script>
        @endpush
    @endforeach
@endsection
