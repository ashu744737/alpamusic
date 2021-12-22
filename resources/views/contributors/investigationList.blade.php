@extends('layouts.master')

@section('title') Dashboard @endsection

@section('headerCss')
<!-- headerCss -->
<!-- DataTables -->
<link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />


@endsection

@section('content')

<!-- content -->
<!-- start page title -->
<!--<div class="row">

        <div class="col-sm-6">
            <div class="page-title-box">
                <h4>Investigators</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Investigators Listing</li>
                </ol>
            </div>
        </div>
        <div class="col-sm-6">

        </div>
    </div>-->
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row align-items-center">
                    <div class="col-12 col-sm-4 col-xs-12">
                        <h4 class="card-title">Investigations List</h4>
                    </div>
                    <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-3">
                        <a href="#" class="btn btn-primary w-md add-new-btn">Add
                            Investigation</a>
                    </div>
                </div>
                <!-- <hr /> -->
                <div class="row">
                    <div class="col-12">
                        <button class="btn collapse-toggle hidden-lg hidden-md btn-block mobile_drop" type="button"
                            data-toggle="collapse" data-target="#investigation_status" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="text-uppercase">
                                <div class="selected_nav">
                                    <span class="tab_title">All</span>
                                    <span class="badge badge-light">62</span>
                                </div>
                                <span class="caret"></span>
                            </span>
                        </button>
                        <div class="list-group collapse" id="investigation_status">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified investigation_status mt-lg-2 py-lg-3 mt-0 py-0"
                                role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#inv_all" role="tab">
                                        <span class="tab_title">All</span>
                                        <span class="badge badge-light">62</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_open" role="tab">
                                        <span class="tab_title">Open</span>
                                        <span class="badge badge-light">30</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_assigned" role="tab">
                                        <span class="tab_title">Assigned</span>
                                        <span class="badge badge-light">8</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_started" role="tab">
                                        <span class="tab_title">Investigation started</span>
                                        <span class="badge badge-light">8</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_report" role="tab">
                                        <span class="tab_title">Report Writing</span>
                                        <span class="badge badge-light">2</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_submitted" role="tab">
                                        <span class="tab_title">Report Submitted</span>
                                        <span class="badge badge-light">6</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_required" role="tab">
                                        <span class="tab_title">Modification Required</span>
                                        <span class="badge badge-light">3</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_delivered" role="tab">
                                        <span class="tab_title">Delivered</span>
                                        <span class="badge badge-light">4</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_late" role="tab">
                                        <span class="tab_title">Late</span>
                                        <span class="badge badge-light">1</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab panes -->
                        <div class="tab-content pt-3 pt-md-0 pt-lg-0">
                            <div class="tab-pane active p-3" id="inv_all" role="tabpanel">
                                <table id="datatable-investigation-all"
                                    class="table table-striped table-bordered dt-responsive"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="select_col" align="center" valign="center" style="width: 20px;">
                                                <input type="checkbox" id="select-allInv"></th>
                                            <th>Type of Investigation</th>
                                            <th>File No.</th>
                                            <th>Investigator Name</th>
                                            <th>Date Started</th>
                                            <th>Date Ended</th>
                                            <th style="text-align:center">Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>
                                            <td>2012/06/22</td>
                                            <td>2012/06/27</td>
                                            <td align="center"><span class="badge badge-success">Delivered</span></td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV102</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>
                                            <td>2011/07/14</td>
                                            <td>-</td>
                                            <td align="center"><span class="badge badge-info">Open</span></td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assult</td>
                                            <td>INV112</td>
                                            <td><a href="javascript:void(0);">John</a></td>
                                            <td>2015/09/23</td>
                                            <td>2016/01/11</td>
                                            <td align="center"><span class="badge badge-warning">Modification
                                                    Required</span></td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV112</td>
                                            <td><a href="javascript:void(0);">Matt</a></td>
                                            <td>2016/06/09</td>
                                            <td>2016/06/17</td>
                                            <td align="center"><span class="badge badge-primary">Report Submitted</span>
                                            </td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV146</td>
                                            <td><a href="javascript:void(0);">Rio</a></td>
                                            <td>2016/08/16</td>
                                            <td>2016/08/29</td>
                                            <td align="center"><span class="badge badge-danger">Late</span></td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane p-0 p-md-3 p-lg-3" id="inv_open" role="tabpanel">
                                <table id="datatable-investigation-open"
                                    class="table table-striped table-bordered dt-responsive"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="select_col" align="center" valign="center" style="width: 20px;">
                                                <input type="checkbox" id="select-all"></th>
                                            <th>Type of Investigation</th>
                                            <th>File No.</th>
                                            <th>Investigator Name</th>

                                            <th>Date Started</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>
                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane p-0 p-md-3 p-lg-3" id="inv_assigned" role="tabpanel">
                                <table id="datatable-investigation-assigned"
                                    class="table table-striped table-bordered dt-responsive"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="select_col" align="center" valign="center" style="width: 20px;">
                                                <input type="checkbox" id="select-all-assigned"></th>
                                            <th>Type of Investigation</th>
                                            <th>File No.</th>
                                            <th>Investigator Name</th>

                                            <th>Date Started</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Theft</td>
                                            <td>INV162</td>
                                            <td><a href="javascript:void(0);">Carry</a></td>

                                            <td>2012/06/22</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Assault</td>
                                            <td>INV170</td>
                                            <td><a href="javascript:void(0);">Chris</a></td>

                                            <td>2012/02/12</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="select_row"></td>
                                            <td>Fraud</td>
                                            <td>INV158</td>
                                            <td><a href="javascript:void(0);">Tom</a></td>

                                            <td>2011/04/25</td>
                                            <td>
                                                <div class="action_btn">
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-file-pdf"></i></a>
                                                    <a href="javascript:void(0);" class="mr-3"><i
                                                            class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0);"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane p-3" id="inv_started" role="tabpanel"></div>
                            <div class="tab-pane p-3" id="inv_report" role="tabpanel"></div>
                            <div class="tab-pane p-3" id="inv_submitted" role="tabpanel"></div>
                            <div class="tab-pane p-3" id="inv_required" role="tabpanel"></div>
                            <div class="tab-pane p-3" id="inv_delivered" role="tabpanel"></div>
                            <div class="tab-pane p-3" id="inv_late" role="tabpanel"></div>
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
<!-- footerScript -->
<!-- Required datatable js -->
<script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>

<script>
    $(document).ready(function () {
        // Open
        var table = $('#datatable-investigation-all').DataTable({
            responsive: true,
            search: true,
            lengthChange: true,
            buttons: ['excel', 'pdf'],
            'order': [],
        });
        table.buttons().container().appendTo('#datatable-investigation-all_wrapper .col-md-6:eq(0)');

        // Open
        var table = $('#datatable-investigation-open').DataTable({
            responsive: true,
            search: true,
            lengthChange: true,
            buttons: ['excel', 'pdf'],
            'order': [],
        });
        table.buttons().container().appendTo('#datatable-investigation-open_wrapper .col-md-6:eq(0)');

        // Assigned
        var table = $('#datatable-investigation-assigned').DataTable({
            responsive: true,
            search: true,
            lengthChange: true,
            buttons: ['excel', 'pdf'],
            'order': [],
        });
        table.buttons().container().appendTo('#datatable-investigation-assigned_wrapper .col-md-6:eq(0)');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust()
                .responsive.recalc();
        });

        // Select All Checkbox
        $('#select-all, #select-all-assigned, #select-allInv').click(function () {
            $('.select_row').prop('checked', this.checked);
        });

        $('.select_row').change(function () {
            var check = ($('.select_row').filter(":checked").length == $('.select_row').length);
            $('#select-all').prop("checked", check);
        });

        // List Group
        $(".list-group a").on('click', function (e) {
            $(this).closest('.list-group').removeClass('show');
            //e.stopPropagation();
        });
    });
</script>
@endsection