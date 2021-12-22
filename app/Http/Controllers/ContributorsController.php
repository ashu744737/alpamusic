<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use App\AssignedInvestigator;
use App\ClientProduct;
use App\ContactTypes;
use App\Country;
use App\Helpers\AppHelper;
use App\Investigations;
use App\InvestigationTransition;
use App\InvestigatorBankDetail;
use App\InvestigatorInvestigations;
use App\InvestigatorProduct;
use App\Investigators;
use App\InvestigatorSpecilization;
use App\Mail\EmailClientApproval;
use App\Mail\EmailVerify;
use App\PaymentMode;
use App\PaymentTerm;
use App\Product;
use App\Specialization;
use App\User;
use App\UserAddress;
use App\UserEmail;
use App\UserPhone;
use App\UserTypes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use View, DB, Log;
use Auth;
use App\Contributor;


class ContributorsController extends Controller
{
    
}
