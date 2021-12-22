@extends('layouts.master')

@section('title') Title Details @endsection

@section('headerCss')
@endsection

@section('content')
    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{route('titles')}}">Titles</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $title->name ?? '-' }}
                    @if ($title->isadminconfirmed == 1)
                        <span class="badge dt-badge badge-success">{{ ucwords('Approved')}}</span>   
                    @else
                        <span class="badge dt-badge badge-warning">{{ ucwords('Pending')}}</span> 
                    @endif   
               </a></li>
            </ol>
        </div>
    </div>
    <!-- content -->
    <div class="row">
        <div class="col-12">
            <div class="card investogators_Details">
                <div class="card-body">
                    
    
                    <div class="row">
                        <div class="col-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#basic_details" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">Title Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#contributors" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-address-book"></i></span>
                                        <span class="d-none d-sm-block">Contributors</span>
                                    </a>
                                </li>
                                
                            </ul>
    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2">Title Details</h4>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">{{ trans('form.registration.client.viewform.name') }}</label>
                                                <div class="col-8">
                                                    {{ $title->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">Created By</label>
                                                <div class="col-8">
                                                    {{ $title->user->name }}
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                                                        
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2">Categories</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                              <div>
                                                
                                                    @foreach($title->categories as $category) 
                                                    @if ($category->name || $category->hr_name)   
                                                    <span class="badge dt-badge badge-primary">
                                                    @if(config('app.locale') == 'hr')
                                                        {{ $category->hr_name ?? '' }}
                                                    @else
                                                        {{ $category->name ?? '' }}
                                                    @endif
                                                    </span>
                                                    @endif
                                                    @endforeach   
                                                </div>      
                                        </div>
                                    </div>   
{{-- 
                                    <hr/>
                                    <h4 class="section-title mb-3 pb-2">Owner Details</h4>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">First Name</label>
                                                <div class="col-8">
                                                    {{ $title->owner[0]['first_name'] ?? '-'}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">Last Name</label>
                                                <div class="col-8">
                                                    {{ $title->owner[0]['last_name'] ?? '-'}}
                                                </div>
                                            </div>
                                        </div>
                                         
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">Email Id</label>
                                                <div class="col-8">
                                                    {{ $title->owner[0]['email'] ?? '-'}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">Type</label>
                                                <div class="col-8">
                                                   
                                                    @foreach($title->categories as $category) 
                                                        @if ($category->name || $category->hr_name)   
                                                            <span class="badge dt-badge badge-primary">
                                                        
                                                            {{ $category->name ?? '' }}
                                                    
                                                        </span>
                                                        @endif
                                                    @endforeach   
                                                </div>
                                            </div>
                                        </div>
                                          
                                    </div> --}}
                                    <hr>
                                    
                                    <h4 class="section-title mb-3 pb-2">Music Files</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                {{-- <th>{{ trans('general.sr_no') }}</th> --}}
                                                                <th>Download Files</th>                                                         
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(count($title->files)>0)
                                                            @php $i = 1; @endphp
                                                            @foreach($title->files as $file)

                                                            <tr>
                                                                {{-- <td>{{$i}}</td> --}}
                                                                <td><a href="{{ asset($file->file_path) }}" target="_blank">
                                                                    <button class="btn"><i class="fa fa-download"></i> Download File</button>
                                                                </a></td>
                                                            </tr>
                                                            @php $i++; @endphp
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   No Records Added
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
    
                              
                            
                                <!-- owners -->
                                <div class="tab-pane p-3" id="contributors" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2">Owner Details</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ trans('general.sr_no') }}</th>
                                                                <th>First Name</th>
                                                                <th>Last Name</th>
                                                                <th>Email</th>
                                                                <th>Types</th>
                                                              
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(count($title->owner)>0)
                                                            @php $i = 1; @endphp
                                                            @foreach($title->owner as $contributor)
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>{{ $contributor->first_name ?? '-' }}</td>
                                                                <td>{{ $contributor->last_name ?? '-' }}</td>
                                                                <td>{{ $contributor->email ?? '-' }}</td>
                                                                
                                                                <td>

                                                                    @foreach($contributor->types as $type) 
                                                                    @if ($type->type)   
                                                                    <span class="badge dt-badge badge-primary">
                                                                   
                                                                        {{ $type->type ?? '' }}
                                                                   
                                                                    </span>
                                                                    @endif
                                                                    @endforeach  
                                                                </td>
                                                            </tr>
                                                            @php $i++; @endphp
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   No Records Added
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <hr>
                                    <h4 class="section-title mb-3 pb-2">Contributor Details</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ trans('general.sr_no') }}</th>
                                                                <th>First Name</th>
                                                                <th>Last Name</th>
                                                                <th>Email</th>
                                                                <th>Types</th>
                                                              
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(count($title->contributors)>0)
                                                            @php $i = 1; @endphp
                                                            @foreach($title->contributors as $contributor)
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>{{ $contributor->first_name ?? '-' }}</td>
                                                                <td>{{ $contributor->last_name ?? '-' }}</td>
                                                                <td>{{ $contributor->email ?? '-' }}</td>
                                                                
                                                                <td>

                                                                    @foreach($contributor->types as $type) 
                                                                    @if ($type->type)   
                                                                    <span class="badge dt-badge badge-primary">
                                                                   
                                                                        {{ $type->type ?? '' }}
                                                                   
                                                                    </span>
                                                                    @endif
                                                                    @endforeach  
                                                                </td>
                                                            </tr>
                                                            @php $i++; @endphp
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   No Records Added
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
    
                         
                            </div>
    
    
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->                            

@endsection

@section('footerScript')
   <script src="{{ URL::asset('/libs/select2/select2.min.js')}}"></script>
   <script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>  
@endsection