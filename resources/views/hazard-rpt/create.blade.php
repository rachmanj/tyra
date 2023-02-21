@extends('templates.main')

@section('title_page')
    New Hazard Report
@endsection

@section('breadcrumb_title')
    hazard-report
@endsection

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <a href="{{ route('hazard-rpt.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-undo"></i> Back</a>
                </div>

                <form action="{{ route('hazard-rpt.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nomor">Report No <small>(Automatic)</small></label>
                                    <input name="nomor" id="nomor" value="{{ $nomor }}" class="form-control @error('nomor') is-invalid @enderror" disabled>
                                    @error('nomor')
                                      <div class="invalid-feedback">
                                        {{ $message }}
                                      </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="date_time">Date & Time <small>(Date & time will change according to the submit time)</small></label>
                                    <input name="date_time" id="date_time" class="form-control" value="{{ $date_time }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="project_code">Project</label>
                                    <select name="project_code" id="project_code" class="form-control select2bs4 @error('project_code') is-invalid @enderror">
                                      <option value="">-- select project --</option>
                                      @foreach ($projects as $project)
                                          <option value="{{ $project }}">{{ $project }}</option>
                                      @endforeach
                                    </select>
                                    @error('project_code')
                                      <div class="invalid-feedback">
                                        {{ $message }}
                                      </div>
                                    @enderror
                                  </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="to_department_id">To Department</label>
                                    <select name="to_department_id" id="to_department_id" class="form-control select2bs4 @error('to_department_id') is-invalid @enderror">
                                      <option value="">-- select department --</option>
                                      @foreach ($departments as $department)
                                          <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                      @endforeach
                                    </select>
                                    @error('to_department_id')
                                      <div class="invalid-feedback">
                                        {{ $message }}
                                      </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label for="category">Category</label>
                              <select name="category" id="category" class="form-control select2bs4 @error('category') is-invalid @enderror">
                                <option value="">-- select Category --</option>
                                <option value="KTA">KTA</option>
                                <option value="TTA">TTA</option>
                              </select>
                              @error('category')
                                <div class="invalid-feedback">
                                  {{ $message }}
                                </div>
                              @enderror
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-group">
                              <label for="danger_type_id">Danger Type</label>
                              <select name="danger_type_id" id="danger_type_id" class="form-control select2bs4 @error('danger_type_id') is-invalid @enderror">
                                <option value="">-- select Danger Types --</option>
                                @foreach ($danger_types as $danger_type)
                                    <option value="{{ $danger_type->id }}">{{ $danger_type->name }}</option>
                                @endforeach
                              </select>
                              @error('danger_type_id')
                                <div class="invalid-feedback">
                                  {{ $message }}
                                </div>
                              @enderror
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12">
                            {{-- text area of description --}}
                            <div class="form-group">
                              <label for="description">Description</label>
                              <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                              @error('description')
                                <div class="invalid-feedback">
                                  {{ $message }}
                                </div>
                              @enderror
                            </div>
                          </div>
                        </div>
                      
                    </div> <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-envelope"></i> Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection