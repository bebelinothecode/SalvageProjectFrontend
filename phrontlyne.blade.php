@extends('layouts.default')
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-left mb-0"> New Policy</h2>
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Forms</a>
                </li>
                <li class="breadcrumb-item active"> {{ Str::upper($customers->fullname) }} ( {{ $customers->account_number }} )
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- <li class="breadcrumb-item active">  
                              </li> -->
      <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrum-right">
          <div class="dropdown">
            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a></div>
          </div>
        </div>
      </div>
    </div>




    <!-- Form wizard with step validation section start -->
    <section id="validation">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-content">
              <div class="content-body font-small-2">

                <!-- Basic Alerts start -->
                <form id="masterform" name="masterform" action="#" class="steps-validation wizard-circle">
                  <!-- Step 1 -->
                  <h6><i class="step-icon feather icon-award"></i> General </h6>


                  <fieldset>


                    <div class="alert alert-primary mb-2" role="alert">

                    </div>

                    <input type="hidden" id="policy_number" name="policy_number" value="" readonly="true" class="form-control" rows="3" tabindex="1">
                    <input type="hidden" id="endorsement_number" name="endorsement_number" value="{{ uniqid(20) }}" readonly="true" class="form-control" rows="3" tabindex="1">
                    <input type="hidden" id="master_policy_number" name="master_policy_number" value="This is a draft schedule" readonly="true" class="form-control" rows="3" tabindex="1">
                    <input type="hidden" class="form-control" id="customer_number" data-required="true" name="customer_number" value="{{$customers->account_number}}" readonly="true">
                    <input type="hidden" class="form-control" id="customer_type" data-required="true" name="customer_type" value="{{$customers->account_type}}" readonly="true">
                    <input type="hidden" name="mycoverage" id="mycoverage" value="">
                    <input type="hidden" class="form-control" id="applied_exchange" name="applied_exchange" value="1">
                    <input type="hidden" class="form-control" readonly id="fullname" name="fullname" value="{{ strtoupper($customers->fullname) }}">




                    <div class="row">

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="policy_product">
                            Policy Type <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <select class="custom-select form-control required" id="policy_product" onchange="generatePolicyNumber();getproductform();setEndDate();loadPolicyClause();getExchangeRate();" name="policy_product" style="width: 100%;">
                            <option value=""> -- Select Policy Type -- </option>
                            @foreach($producttypes as $producttype)
                            <option value="{{ $producttype->type }}"> {{$producttype->type }}</option>
                            @endforeach
                          </select>

                        </div>

                      </div>


                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="policy_branch">
                            Branch / Unit <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <select class="custom-select form-control required" onchange="loadWorkgroups()" id="policy_branch" name="policy_branch">
                            @foreach($branches as $branch)
                            <option @if (Auth::user()->getBranch() == $branch->branch_name)
                              selected
                              @endif
                              value="{{ $branch->branch_name }}">{{ $branch->branch_name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>


                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="location">
                            Period Rating Factor <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <select class="custom-select form-control required" id="rating_factor" name="rating_factor" onchange="computeShortPeriodNonMotor(this);setPeriodDays();">
                            @foreach($shortperiods as $shortperiod)
                            <option value="{{ $shortperiod->type }}">{{ $shortperiod->type }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <div class="row">
                      {{-- <div class="col-md-4" id="period_days_div">
                        <div class="form-group">
                          <label for="period_days">
                            Days  <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <input type="number" onchange="setEndDate()" class="form-control pickadate" id="period_days" name="period_days">
                        </div>
                      </div> --}}


                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailAddress5">
                            Commencement Date <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <input type="text" onchange="setEndDate();computenonMotorPremium(); " class="form-control pickadate" id="commence_date" name="commence_date">
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailAddress5">
                            Expiry Date <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <input type="text" onchange="calculateShortPeriodDays(); computenonMotorPremium();" class="form-control" id="expiry_date" name="expiry_date">
                        </div>
                      </div>


                      <div class="col-md-4">
                        <div id="bonddate" name="bonddate">

                          <div class="form-group">
                            <label for="location">
                              Submission Date / Award Letter Date
                            </label>
                            <input type="text" class="form-control" id="acceptance_date" name="acceptance_date">
                          </div>
                        </div>
                      </div>

                    </div>


                    <hr>


                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailAddress5">
                            {{ $labels->policy_workgroup}} <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <select class="custom-select form-control required" id="policy_workgroup" name="policy_workgroup" data-required="true" onchange="loadWorkgroupSources();">
                            <option value=""> -- Select a {{ $labels->policy_workgroup}} -- </option>
                            @foreach($workgroups as $workgroup)
                            <option value="{{ $workgroup->type }}"> {{$workgroup->type }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      {{-- <div class="form-group">
                        <label for="emailAddress5">
                          Intermediary Type <sup class="text-danger font-medium-1"> * </sup>
                        </label>
                        <select id="policy_sales_type" name="policy_sales_type" onchange="loadIntermediary();getcoinsuranceform();" class="custom-select form-control required" >
                          <option value="">-- not set --</option>
                       
                        </select>
                      </div> --}}

                      {{-- <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailAddress5">
                            {{ $labels->policy_sales_type}} <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <select id="policy_sales_type" name="policy_sales_type" onchange="loadIntermediary();getcoinsuranceform();" class="custom-select form-control required">
                            <option value="">-- not set --</option>
                              @foreach($workgroups as $workgroup)
                              <option value="{{ $workgroup->type }}"> {{$workgroup->type }}</option>
                              @endforeach
                          </select>
                        </div>
                      </div> --}}

                      {{-- <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailAddress5">
                            Intermediary Type <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <select id="policy_sales_type" name="policy_sales_type" onchange="loadIntermediary();getcoinsuranceform();" class="custom-select form-control required" >
                            <option value="">-- not set --</option>
                         
                          </select>
                        </div>
                      </div> --}}
                      {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="location">
                              Intermediary's Name <sup class="text-danger font-medium-1"> * </sup>
                            </label>
                            <select id="agency" name="agency" class="custom-select form-control required" >
                              <option value="">-- not set --</option>
                       
                            </select> 
                        </div>
                    </div> --}}
                    </div>

                    <hr>



                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailAddress5">
                            Currency <sup class="text-danger font-medium-1"> * </sup>
                          </label>
                          <select class="custom-select form-control required" id="policy_currency" onchange="getExchangeRate(this)" name="policy_currency" data-required="true">
                            <!-- <option value=""> -- Select a currency -- </option> -->
                            @foreach($currencies as $currency)
                            <option value="{{ $currency->type }}">{{ $currency->type }} @ {{ $currency->rate }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailAddress5">
                            {{ $labels->account_manager}}
                          </label>
                          <select id="account_manager" name="account_manager" class="custom-select form-control required">
                            <option value="No  {{ $labels->account_manager}}">No {{ $labels->account_manager}} </option>
                            @foreach($users as $user)
                            <option value="{{ $user->fullname }}">{{ $user->fullname }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="location">
                            Campaign
                          </label>
                          <select id="policy_sales_channel" name="policy_sales_channel" class="custom-select form-control required">
                            <option value=""></option>
                            @foreach($collectionmodes as $collectionmodes)
                            <option value="{{ $collectionmodes->type }}">{{ $collectionmodes->type }}</option>
                            @endforeach

                          </select>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <div class="row">
                      <div id="general_introducer">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="emailAddress5">
                              {{ $labels->introducer}}
                            </label>
                            <select class="custom-select form-control required" id="introducer" name="introducer" data-required="true">
                              <option value="No  {{ $labels->introducer}}">No {{ $labels->introducer}} </option>
                              @foreach($users as $user)
                              <option value="{{ $user->fullname }}">{{ $user->fullname }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div id="general_details">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="emailAddress5">
                              Policy Status
                            </label>
                            <select class="custom-select form-control required" id="policy_status" name="policy_status" data-required="true"">
                                <option value=" Draft">Draft</option>
                            </select>
                          </div>
                        </div>


                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="emailAddress5">
                              Transaction Type
                            </label>
                            <select id="transaction_type" name="transaction_type" class="custom-select form-control required">

                              <option value="First Premium">First Premium</option>
                              <option value="R/I FAC. In Premium">R/I FAC. In Premium</option>

                            </select>
                          </div>
                        </div>
                      </div>

                      <input type="hidden" class="form-control" name="transaction_date" readonly="true" id="transaction_date" placeholder="Select your time" value="{{ old('transaction_date') }}">
                      <input type="hidden" class="form-control" name="issue_date" id="issue_date" placeholder="Select your time" value="{{ old('issue_date') }}">

                    </div>




                    <div class="alert alert-primary mb-2" role="alert">

                    </div>


                  </fieldset>

                  <!-- Step 2 -->


                  <!-- Step 2 -->
                  <h6><i class="step-icon feather icon-umbrella"></i> Risk </h6>
                  <fieldset>

                    <div class="alert alert-primary mb-2" role="alert">

                    </div>



                    <!-- Accordion with margin start || Fire Schedule-->
                    <div id="fireinsurance" name="fireinsurance">
                      <section id="accordion-with-margin">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="card collapse-icon accordion-icon-rotate">

                              <div class="card-body">

                                <div class="accordion" id="accordionFire">


                                  <div class="collapse-margin">
                                    <div class="card-header" id="headingTwo" data-toggle="collapse" role="button" data-target="#collapseFireTwo" aria-expanded="false" aria-controls="collapseFireTwo">
                                      <span class="lead collapse-title">

                                      </span>
                                    </div>
                                    <div id="collapseFireTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionFire">
                                      <div class="card-body">
                                        <!-- // Basic multiple Column Form section start -->
                                        <section id="multiple-column-form">
                                          <div class="row match-height">
                                            <div class="col-12">
                                              <div class="card">

                                                <div class="card-content">
                                                  <div class="card-body">

                                                    <div class="form-body">
                                                      <div class="row">

                                                        <div class="col-md-2">
                                                          <div class="form-group">
                                                            <label for="policy_product">
                                                              Risk Type<sup class="text-danger font-medium-1"> * </sup>
                                                            </label>
                                                            <input type="hidden" id="firekey" name="firekey" value="{{ Request::old('firekey') ?: '' }}">
                                                            <select id="fire_risk_covered" name="fire_risk_covered" required onchange="getCommissionRates(this);showFireblanket(this)" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                                              @foreach($firerisks as $firerisks)
                                                              <option value="{{ $firerisks->type }}">{{ $firerisks->type }}</option>
                                                              @endforeach
                                                            </select>

                                                          </div>



                                                        </div>

                                                        <div class="col-md-2">
                                                          <div class="form-group">
                                                            <label for="policy_product">
                                                              Property / Item Type <sup class="text-danger font-medium-1"> * </sup>
                                                            </label>

                                                            <select id="property_type" name="property_type" required onchange="notbuilding()" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                                              @foreach($propertytypes as $property)
                                                              <option value="{{ $property->type }}">{{ $property->type }}</option>
                                                              @endforeach
                                                            </select>


                                                          </div>

                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                          <label for="country-floating">Risk Number <sup class="text-danger font-medium-1"> * </sup></label>
                                                          <div class="form-label-group">
                                                            <input type="number" min="1" class="form-control" id="property_number_item" placeholder="Risk Number" value="1" name="property_number_item">

                                                          </div>
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                          <label for="company-column">Item Number <sup class="text-danger font-medium-1"> * </sup></label>
                                                          <div class="form-label-group">
                                                            <input type="number" min="1" class="form-control" id="property_item_number" placeholder="Item Number" value="1" name="property_item_number">

                                                          </div>
                                                        </div>


                                                        <div class="col-md-2 col-12">
                                                          <label for="country-floating">Risk Value / Sum Insured <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <input type="text" min="0" step="0.001" rows="3" class="form-control" id="item_value" name="item_value" value="{{ Request::old('item_value') ?: '' }}">

                                                          </div>
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                          <label for="company-column">Basic Rate (%) <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <input type="number" min="0" step="0.001" class="form-control" id="fire_rate" value="{{ Request::old('fire_rate') ?: '' }}" name="fire_rate">

                                                          </div>
                                                        </div>


                                                      </div>

                                                      <br>


                                                      <div class="row">

                                                        <div class="col-md-8">
                                                          <div id="notproperty">
                                                            <label for="email-id-column"> Risk Address <sup class="text-danger font-medium-1"> * </sup> </label>
                                                            <div class="form-label-group">
                                                              <input type="text" rows="3" class="form-control block-mask text-uppercase" placeholder="Risk / Property Address" id="property_address" name="property_address" value="{{ Request::old('property_address') ?: '' }}"></input>

                                                            </div>
                                                          </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                          <label for="email-id-column"> GPS Number </label>
                                                          <div class="form-label-group">
                                                            <input type="text" class="form-control block-mask text-uppercase" id="unit_number" placeholder="Ghana Post Number" value="{{ Request::old('unit_number') ?: '' }}" name="unit_number">

                                                          </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                          <label for="email-id-column">Fire Certifcate Number</label>
                                                          <div class="form-label-group">
                                                            <input type="number" class="form-control block-mask text-uppercase" id="property_sticker_number" placeholder="Sticker Number" value="{{ Request::old('property_sticker_number') ?: '' }}" name="property_sticker_number">

                                                          </div>
                                                        </div>



                                                      </div>

                                                      <div class="row">

                                                        <div class="col-md-12 col-12">
                                                          <label for="email-id-column"> Risk Description <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <textarea id="property_description" name="property_description">  </textarea>

                                                          </div>
                                                        </div>
                                                      </div>
                                                      <br>



                                                      <code>Discounts & Loadings</code>
                                                      <div class="alert alert-primary mb-2" role="alert">

                                                      </div>
                                                      <div class="row">

                                                        <div class="col-md-2 col-12">
                                                          <div class="d-flex align-items-center mb-1  pull-right">
                                                            LTA
                                                            <div class="input-group input-group-lg">
                                                              <input type="number" id="lta" step="0.01" class="touchspin-color" data-bts-button-down-class="btn btn-success" data-bts-button-up-class="btn btn-success" value="0" placeholder="Quantity" name="lta">
                                                            </div>
                                                          </div>

                                                          <div class="d-flex align-items-center mb-1 pull-right">
                                                            Fire Extinguisher
                                                            <div class="input-group input-group-lg">
                                                              <input type="number" id="fire_extinguisher" step="0.01" class="touchspin-color" data-bts-button-down-class="btn btn-success" data-bts-button-up-class="btn btn-success" value="0" placeholder="Quantity" name="fire_extinguisher">
                                                            </div>
                                                          </div>
                                                        </div>





                                                        <div class="col-md-2 col-12">
                                                          <div class="d-flex align-items-center mb-1 pull-right">
                                                            Fire Hydrant
                                                            <div class="input-group input-group-lg">
                                                              <input type="number" id="fire_hydrant" step="0.01" class="touchspin-color" data-bts-button-down-class="btn btn-success" data-bts-button-up-class="btn btn-success" value="0" placeholder="Quantity" name="fire_hydrant">
                                                            </div>
                                                          </div>

                                                          <div class="d-flex align-items-center mb-1 pull-right">
                                                            SD
                                                            <div class="input-group input-group-lg">
                                                              <input type="number" id="Fire Hydrant" step="0.01" class="touchspin-color" data-bts-button-down-class="btn btn-success" data-bts-button-up-class="btn btn-success" value="0" placeholder="Quantity" name="Fire Hydrant">
                                                            </div>
                                                          </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                          <div class="d-flex align-items-center mb-1 pull-right">
                                                            Collapse Rate (%)
                                                            <div class="input-group input-group-lg">
                                                              <input type="number" id="collapserate" step="0.01" class="touchspin-color" data-bts-button-down-class="btn btn-danger" data-bts-button-up-class="btn btn-danger" value="0" placeholder="Quantity" name="collapserate">
                                                            </div>
                                                          </div>

                                                          <div class="d-flex align-items-center mb-1 pull-right">
                                                            Service Levy (%)
                                                            <div class="input-group input-group-lg">
                                                              <input type="number" id="levy_rate" class="touchspin-color" data-bts-button-down-class="btn btn-danger" data-bts-button-up-class="btn btn-danger" value="0" placeholder="Quantity" name="levy_rate">
                                                            </div>
                                                          </div>
                                                        </div>

                                                        <!-- <div class="col-md-2 col-12">
                                                                              <div class="form-label-group">
                                                                                <input type="text" value="0" class="form-control"  id="lta" name="lta">
                                                                                  <label for="country-floating">LTA Discount - max 10%</label>
                                                                              </div>
                                                                          </div> -->
                                                        <!-- <div class="col-md-2 col-12">
                                                                              <div class="form-label-group">
                                                                                <input type="text" value="0" class="form-control" id="fire_extinguisher" name="fire_extinguisher">
                                                                                  <label for="company-column">Fire Extinguisher max 5%</label>
                                                                              </div>
                                                                          </div> -->


                                                        <!-- <div class="col-md-2 col-12">
                                                                            <div class="form-label-group">
                                                                              <input type="text" value="0" class="form-control" id="fire_hydrant" name="fire_hydrant">
                                                                                <label for="country-floating">Fire Hydrant max 7.5%</label>
                                                                            </div>
                                                                        </div>
                  
                  
                                                                        <div class="col-md-2 col-12">
                                                                            <div class="form-label-group">
                                                                              <input type="text" value="0" readonly="true" class="form-control" id="staff_discount" name="staff_discount">
                                                                                <label for="company-column">SD (%)</label>
                                                                            </div>
                                                                        </div> -->







                                                        <!-- <div class="col-md-2 col-12">
                                                                              <div class="form-label-group">
                                                                                <input type="text" value="0" placeholder="Collapse Rate (%)" class="form-control" id="collapserate" name="collapserate">
                                                                                  <label for="country-floating">Collapse Rate (%)</label>
                                                                              </div>
                                                                          </div>
                                                                          <div class="col-md-2 col-12">
                                                                              <div class="form-label-group">
                                                                                <input type="text" value="0" placeholder="Service Levy (%)" class="form-control" id="levy_rate" name="levy_rate">
                                                                                  <label for="company-column">Service Levy (%)</label>
                                                                              </div>
                                                                          </div> -->

                                                      </div>
                                                      <div class="alert alert-primary mb-2" role="alert">

                                                      </div>







                                                      <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">

                                                        <button type="button" onclick="addProperty()" name="btnfire" id="btnfire" class="btn btn-primary mr-1 mb-1">Add Risk / Item</button>
                                                        <input type="hidden" id="firepremium" name="firepremium" value="">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="text-info" id="add_up_fire" name="add_up_fire" value="Add Up Fire" data-toggle="tooltip" data-placement="top" title="" data-original-title="Checking this box will cause Phrontlyne to add up the selected UOM to Sum Insured" checked>



                                                      </div>

                                                    </div>



                                                  </div>
                                                  <!-- <img src="/images/coverage-skyline-desktop.png" style="width: 100%;"> -->
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </section>
                                        <!-- // Basic Floating Label Form section end -->

                                      </div>
                                    </div>
                                  </div>

                                  <div class="collapse-margin">
                                    <div class="card-header" id="headingFireOne" data-toggle="collapse" role="button" data-target="#collapseFireOne" aria-expanded="false" aria-controls="collapseFireOne">
                                      <span class="lead collapse-title">

                                      </span>
                                    </div>

                                    <div id="collapseFireOne" class="collapse show" aria-labelledby="headingFireOne" data-parent="#accordionFire">
                                      <div class="card-body">
                                        <div class="panel-body">
                                          <div class="table-responsive">
                                            <table id="fireRiskTable" class="table table-striped table-hover mb-0 font-small-2 text-center">
                                              <thead>
                                                <tr>

                                                  <th>Risk Number</th>
                                                  <th>Item Number</th>
                                                  <th>Description</th>
                                                  <th>Sum Insured</th>
                                                  <th>Rate</th>
                                                  <th>Premium</th>
                                                  <th></th>
                                                  <th></th>
                                                  <th></th>
                                                </tr>
                                              </thead>
                                              <tbody>

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
                      </section>
                      <!-- Accordion with margin end -->
                    </div>






                    <!-- Accordion with margin start || Fire Schedule-->
                    <!-- Accordion with margin start || Fire Schedule-->
                    <div id="bondinsurance" name="bondinsurance">
                      <section id="accordion-with-margin">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="card collapse-icon accordion-icon-rotate">

                              <div class="card-body">

                                <div class="accordion" id="Bondaccordion">

                                  <div class="collapse-margin">
                                    <div class="card-header" id="headingTwo" data-toggle="collapse" role="button" data-target="#BondaccordionTwo" aria-expanded="false" aria-controls="BondaccordionTwo">

                                    </div>
                                    <div id="BondaccordionTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#Bondaccordion">
                                      <div class="card-body">
                                        <!-- // Basic multiple Column Form section start -->
                                        <section id="multiple-column-form">
                                          <div class="row match-height">
                                            <div class="col-12">
                                              <div class="card">

                                                <div class="card-content">
                                                  <div class="card-body">

                                                    <div class="form-body">
                                                      <div class="row">
                                                        <div class="col-md-2 col-12">
                                                          <label for="first-name-column">Risk Type <sup class="text-danger font-medium-1"> * </sup></label>
                                                          <div class="form-label-group">
                                                            <select id="bond_risk_type" name="bond_risk_type" onchange="getCommissionRates(this);CustomReference();loadBondTemplate();" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                                              <option value="">-- Select coverage --</option>
                                                              @foreach($bondtypes as $bondtype)
                                                              <option value="{{ ucwords(strtolower($bondtype->type)) }}">{{ ucwords(strtolower($bondtype->type)) }}</option>
                                                              @endforeach
                                                            </select>

                                                          </div>
                                                        </div>


                                                        <div class="col-md-2 col-12">
                                                          <label for="first-name-column">Category Type <sup class="text-danger font-medium-1"> * </sup></label>
                                                          <div class="form-label-group">
                                                            <select id="bond_risk_type_class" name="bond_risk_type_class" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                                              <option value="">-- Select Category --</option>
                                                              <option value="On Demand">On Demand</option>
                                                              <option value="Conditional">Conditional</option>
                                                            </select>
                                                          </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                          <label for="country-floating">Contract Sum </label>
                                                          <div class="form-label-group">
                                                            <input type="text" min="0" value="0" step="0.01" placeholder="Contract Sum" class="form-control" value="{{ Request::old('contract_sum') ?: '' }}" name="contract_sum" id="contract_sum">

                                                          </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                          <label for="country-floating">Bond Sum <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <input type="text" min="0" value="0" step="0.01" placeholder="Bond Sum" class="form-control" id="bond_sum_insured" value="{{ Request::old('pa_height') ?: '' }}" name="bond_sum_insured">

                                                          </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                          <label for="company-column">Basic Rate (%) <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <input type="number" min="0" value="0" step="0.01" placeholder="Basic Rate (%)" class="form-control" id="bond_rate" value="{{ Request::old('bond_rate') ?: '' }}" name="bond_rate">

                                                          </div>
                                                        </div>




                                                      </div>

                                                      <br>


                                                      <div class="row">
                                                        <div class="col-md-8 col-12">
                                                          <label for="email-id-column"> Principal Name <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <input type="text" class="form-control block-mask text-uppercase" id="bond_interest" placeholder=" Principal Name" value="{{ Request::old('bond_interest') ?: '' }}" name="bond_interest">

                                                          </div>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                          <label for="email-id-column"> Principal Address <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <input type="text" class="form-control block-mask text-uppercase" id="bond_interest_address" placeholder="Principal Address" value="{{ Request::old('bond_interest_address') ?: '' }}" name="bond_interest_address">

                                                          </div>
                                                        </div>
                                                      </div>
                                                      <br>



                                                      <div class="row">
                                                        <div class="col-md-12 col-12">
                                                          <label for="email-id-column"> Contract Description <sup class="text-danger font-medium-1"> * </sup> </label>
                                                          <div class="form-label-group">
                                                            <textarea type="text" class="form-control block-mask text-uppercase" placeholder="Contract Description" rows="3" id="bond_contract_description" value="{{ Request::old('bond_contract_description') ?: '' }}" name="bond_contract_description"></textarea>

                                                          </div>
                                                        </div>
                                                      </div>
                                                      <br>


                                                      <div id="custom_bonds" name="custom_bonds">

                                                        <div class="alert alert-danger mb-2" role="alert">

                                                        </div>
                                                        <div class="row">

                                                          <div class="col-md-2 col-12">
                                                            <label id="bond_label_1" name="bond_label_1">Declaration Number <sup class="text-danger font-medium-1"> * </sup></label>
                                                            <div class="form-label-group">

                                                              <input type="text" class="form-control block-mask text-uppercase" id="bond_declaration_number" placeholder="" value="{{ Request::old('bond_declaration_number') ?: '' }}" name="bond_declaration_number">


                                                            </div>
                                                          </div>
                                                          <div class="col-md-2 col-12">
                                                            <label id="bond_label_2" name="bond_label_2">Serial Number <sup class="text-danger font-medium-1"> * </sup></label>
                                                            <div class="form-label-group">

                                                              <input type="text" class="form-control block-mask text-uppercase" id="bond_serial_number" placeholder="" value="{{ Request::old('bond_serial_number') ?: '' }}" name="bond_serial_number">



                                                            </div>
                                                          </div>

                                                          <div class="col-md-2 col-12" id="bond_exchange_rate_box" name="bond_exchange_rate_box">
                                                            <label id="bond_label_3" name="bond_label_3">Exchange Rate (For AGENDA 111) <sup class="text-danger font-medium-1"> * </sup></label>
                                                            <div class="form-label-group">
                                                              <input type="text" class="form-control block-mask text-uppercase" id="bond_exchange_rate" placeholder="" value="{{ Request::old('bond_exchange_rate') ?: '' }}" name="bond_exchange_rate">

                                                            </div>
                                                          </div>

                                                          <div class="col-md-2 col-12" id="valid_until_box" name="valid_until_box">
                                                            <label id="bond_label_4" name="bond_label_4">Valid Until <sup class="text-danger font-medium-1"> * </sup></label>
                                                            <div class="form-label-group">
                                                              <input type="text" class="form-control block-mask text-uppercase" id="valid_until" placeholder="" value="{{ Request::old('valid_until') ?: '' }}" name="valid_until">
                                                            </div>
                                                          </div>


                                                          <div class="col-md-4 col-12">
                                                            <label for="first-name-column">Template Type <sup class="text-danger font-medium-1"> * </sup></label>
                                                            <div class="form-label-group">
                                                              <select id="bond_template" name="bond_template" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                                                <option value="">-- Select template --</option>


                                                              </select>

                                                            </div>
                                                          </div>

                                                        </div>



                                                        <div class="alert alert-danger mb-2" role="alert">

                                                        </div>


                                                      </div>







                                                      <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                        <button type="button" name="btnbond" id="btnbond" onclick="addBondDetails()" class="btn btn-success btn-s-xs">Add Bond Risk</button>
                                                        <input type="hidden" id="bondkey" name="bondkey" value="{{ Request::old('bondkey') ?: '' }}">

                                                      </div>


                                                    </div>

                                                  </div>
                                                </div>


                                              </div>
                                            </div>
                                          </div>
                                        </section>
                                        <!-- // Basic Floating Label Form section end -->

                                      </div>
                                    </div>
                                  </div>

                                  <div class="collapse-margin">
                                    <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#BondaccordionOne" aria-expanded="false" aria-controls="BondaccordionOne">

                                    </div>

                                    <div id="BondaccordionOne" class="collapse show" aria-labelledby="headingOne" data-parent="#Bondaccordion">
                                      <div class="card-body">
                                        <div class="panel-body">
                                          <div class="table-responsive">
                                            <table id="bondScheduleTable" class="table table-striped table-hover mb-0 font-small-2 text-center">
                                              <thead>
                                                <tr>

                                                  <th>Risk Type</th>
                                                  <th>Interest</th>
                                                  <th>Description</th>
                                                  <th>Sum Insured</th>
                                                  <th>Rate</th>
                                                  <th>Premium</th>
                                                  <th>Created On</th>
                                                  <th>Created By</th>
                                                  <th></th>
                                                  <th></th>
                                                </tr>
                                              </thead>
                                              <tbody>
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
                      </section>
                      <!-- Accordion with margin end -->
                    </div>





                    <div id="contractorallrisk" name="contractorallrisk">
                      <section id="accordion-with-margin">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="card collapse-icon accordion-icon-rotate">

                              <div class="card-body">

                                <div class="accordion" id="EngineeringAccordion">

                                  <div class="collapse-margin">
                                    <div class="card-header" id="EngineeringAccordionheadingTwo" data-toggle="collapse" role="button" data-target="#EngineeringAccordionTwo" aria-expanded="false" aria-controls="EngineeringAccordionTwo">

                                    </div>
                                    <div id="EngineeringAccordionTwo" class="collapse show" aria-labelledby="EngineeringAccordionheadingTwo" data-parent="#EngineeringAccordion">
                                      <div class="card-body"> 
                                        <!-- // Basic multiple Column Form section start -->
                         <section id="multiple-column-form">
                           <div class="row match-height">
                               <div class="col-12">
    
                              
                                   <div class="card">
                                      
                                       <div class="card-content">
                                           <div class="card-body">
                                               
                                                   <div class="form-body">
                                                       <div class="row">
                                                           <div class="col-md-2 col-12">
                                                            <label for="first-name-column">Risk Type <sup class="text-danger font-medium-1"> * </sup> </label>
                                                               <div class="form-label-group">
                                                                <select id="engineering_risk_type" name="engineering_risk_type" onchange="getCommissionRates(this);" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                                                    <option value="">-- Select coverage --</option>
                                                                       @foreach($engineeringrisktypes as $engineeringrisktypes)
                                                                    <option value="{{ $engineeringrisktypes->type }}">{{ $engineeringrisktypes->type }}</option>
                                        @endforeach
                                        </select>

                                      </div>
                                    </div>


                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Risk <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="number" min="1" value="1" step="1" class="form-control" id="engineering_risk_number" value="{{ Request::old('engineering_risk_number') ?: '' }}" name="engineering_risk_number">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Item <sup class="text-danger font-medium-1"> * </sup></label>
                                      <div class="form-label-group">
                                        <input type="number" min="1" value="1" step="1" class="form-control" id="engineering_item_number" value="{{ Request::old('engineering_item_number') ?: '' }}" name="engineering_item_number">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Unit of Measure <sup class="text-danger font-medium-1"> * </sup></label>
                                      <div class="form-label-group">
                                        <select id="engineering_unit" name="engineering_unit" rows="3" tabindex="1" data-placeholder="Select here Unit of Measure (UOM)" style="width:100%">
                                          <option value="">-- Select coverage --</option>
                                          @foreach($engineeringuom as $unit)
                                          <option value="{{ $unit->type }}">{{ $unit->type }}</option>
                                          @endforeach
                                        </select>

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Sum Insured / Limit <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="text" min="0" value="0" step="0.01" value="0" class="form-control" id="engineering_si" value="{{ Request::old('engineering_si') ?: '' }}" name="engineering_si">

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="company-column">Basic Rate (%) <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="number" min="0" value="0" step="0.01" value="0" class="form-control" id="engineering_rate" value="{{ Request::old('engineering_rate') ?: '' }}" name="engineering_rate">

                                      </div>
                                    </div>

                                  </div>



                                  <div class="row">
                                    <div class="col-md-12 col-12">
                                      <label for="email-id-column"> Site of Construction <sup class="text-danger font-medium-1"> * </sup></label>
                                      <div class="form-label-group">
                                        <input type="text" class="form-control" id="car_nature_of_business" placeholder="Site of Construction" value="{{ Request::old('car_nature_of_business') ?: '' }}" name="car_nature_of_business">

                                      </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                      <label for="email-id-column"> Risk Description / Title of Contract <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <textarea id="engineering_risk_description" name="engineering_risk_description">  </textarea>

                                      </div>
                                    </div>
                                  </div>
                                  <br>


                                  <div id="engineering_risk_text" name="engineering_risk_text">
                                    <section id="accordion-with-margin">
                                      <div class="row">
                                        <div class="col-sm-12">
                                          <div class="card collapse-icon accordion-icon-rotate">

                                            <div class="card-body">

                                              <div class="accordion" id="accordionEngineering" data-toggle-hover="true">
                                                <div class="collapse-margin">
                                                  <div class="card-header collapsed" id="headingOne" data-toggle="collapse" role="button" data-target="#collapseOneEngineering" aria-expanded="false" aria-controls="collapseOneEngineering">
                                                    <span class="lead collapse-title font-small-3">
                                                      <code> Risk Upper Text </code>
                                                    </span>
                                                  </div>

                                                  <div id="collapseOneEngineering" class="collapse" aria-labelledby="headingOne" data-parent="#accordionEngineering" style="">
                                                    <div class="card-body">
                                                      <div id="engineering_schedule" name="engineering_schedule" class="form-control" style="overflow:scroll;height:300px;max-height:300px" contenteditable="true">
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="collapse-margin">
                                                    <div class="card-header" id="headingTwo" data-toggle="collapse" role="button" data-target="#collapseTwoEngineering" aria-expanded="false" aria-controls="collapseTwoEngineering">
                                                      <span class="lead collapse-title font-small-3">
                                                        <code> Risk Lower Text </code>
                                                      </span>
                                                    </div>
                                                    <div id="collapseTwoEngineering" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionEngineering">
                                                      <div class="card-body">

                                                        <div id="engineering_beneficiary" name="engineering_beneficiary" class="form-control" style="overflow:scroll;height:300px;max-height:300px" contenteditable="true">
                                                        </div>
                                                      </div>
                                                    </div>


                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                    </section>
                                  </div>







                                  <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">

                                    <button type="button" id="btnengineering" name="btnengineering" onclick="addEngineeringDetails()" class="btn btn-sm btn-primary rounded pull-left waves-effect waves-light">+ Add Item</button>
                                    <input type="hidden" id="engineeringkey" name="engineeringkey" value="{{ Request::old('engineeringkey') ?: '' }}">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="text-info" id="add_up_engineering" name="add_up_engineering" value="Add Up Engineering" data-toggle="tooltip" data-placement="top" title="" data-original-title="Checking this box will cause Phrontlyne to add up the selected UOM to Sum Insured" checked>

                                  </div> 

                                </div>

                                <!-- <img src="/images/stencil-5.png" style="width: 1800px;"> -->

                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
    </section> 
    <!-- // Basic Floating Label Form section end -->

  </div>
</div>
</div>

<div class="collapse-margin">
  <div class="card-header" id="EngineeringAccordionheadingOne" data-toggle="collapse" role="button" data-target="#EngineeringAccordionOne" aria-expanded="false" aria-controls="EngineeringAccordionOne">

  </div>

  <div id="EngineeringAccordionOne" class="collapse show" aria-labelledby="EngineeringAccordionheadingOne" data-parent="#EngineeringAccordion">
    <div class="card-body">
      <div class="panel-body">
        <div class="table-responsive">
          <table id="engineeringScheduleTable" class="table table-striped table-hover mb-0 font-small-2 text-center">
            <thead>
              <tr>

                <th>Risk Number</th>
                <th>Item Number</th>
                <th>Description</th>
                <th>UOM</th>
                <th>Sum Insured</th>
                <th>Rate</th>
                <th>Premium</th>
                <th>Created By</th>
                <th>Created On</th>

                <th></th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>

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
</section>
<!-- Accordion with margin end -->
</div>





<div id="generalaccident" name="generalaccident">
  <section id="accordion-with-margin">
    <div class="row">
      <div class="col-sm-12">
        <div class="card collapse-icon accordion-icon-rotate">

          <div class="card-body">

            <div class="accordion" id="accordionAccident">

              <div class="collapse-margin">
                <div class="card-header" id="accordionAccidentheadingTwo" data-toggle="collapse" role="button" data-target="#accordionAccidentcollapseTwo" aria-expanded="false" aria-controls="accordionAccidentcollapseTwo">

                </div>
                <div id="accordionAccidentcollapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionAccident">
                  <div class="card-body">
                    <!-- // Basic multiple Column Form section start -->
                    <section id="multiple-column-form">
                      <div class="row match-height">
                        <div class="col-12">

                          <div class="alert alert-primary mb-2" role="alert">

                          </div>

                          <div class="card">

                            <div class="card-content">
                              <div class="card-body">

                                <div class="form-body">


                                  <div class="row">

                                    <div class="col-md-2 col-12">
                                      <label for="first-name-column">Risk Type <sup class="text-danger font-medium-1"> * </sup></label>
                                      <div class="form-label-group">
                                        <select id="accident_risk_type" name="accident_risk_type" onchange="getCommissionRates(this);showAccidentMeansOfConveyance(); " rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                          <option value="">-- Select coverage --</option>

                                          @foreach($accidenttypes as $accident)
                                          <option value="{{ $accident->type }}">{{ $accident->type }}</option>
                                          @endforeach
                                        </select>

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Unit of Measure <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <select id="accident_unit" name="accident_unit" rows="3" tabindex="1" data-placeholder="Select here Unit of Measure (UOM)" style="width:100%">
                                          <option value="">-- Select UOM --</option>
                                          @foreach($limitsofmeasures as $unit)
                                          <option value="{{ $unit->type }}">{{ $unit->type }}</option>
                                          @endforeach
                                        </select>

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Risk Number <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="number" min="1" value="1" step="1" class="form-control" id="accident_risk_number" value="{{ Request::old('accident_risk_number') ?: '' }}" name="accident_risk_number">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Item Number <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="number" min="1" value="1" step="1" class="form-control" id="accident_item_number" value="{{ Request::old('accident_item_number') ?: '' }}" name="accident_item_number">

                                      </div>
                                    </div>


                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Sum Insured / Limit <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="text" min="0" value="0" step="0.01" value="0" class="form-control" id="accident_si" value="{{ Request::old('accident_si') ?: '' }}" name="accident_si">

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="company-column">Basic Rate (%) <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="number" min="0" value="0" step="0.01" value="0" class="form-control" id="accident_rate" value="{{ Request::old('accident_rate') ?: '' }}" name="accident_rate">

                                      </div>
                                    </div>

                                    <div class="col-md-4 col-12" id="accident_means_of_conveyance_div">
                                      <label for="first-name-column">Means of Conveyance</label>
                                      <div class="form-label-group">
                                        <input type="text" class="form-control" id="accident_means_of_conveyance" value="{{ Request::old('accident_means_of_conveyance') ?: '' }}" name="accident_means_of_conveyance">
                                      </div>
                                    </div>


                                  </div>

                                  <br>

                                  <div class="row">
                                    <div class="col-md-12 col-12">
                                      <label for="email-id-column"> Risk Description <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <textarea id="accident_risk_description" name="accident_risk_description">  </textarea>

                                      </div>
                                    </div>
                                  </div>
                                  <br>


                                  <section id="accordion-with-margin">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="card collapse-icon accordion-icon-rotate">

                                          <div class="card-body">

                                            <div class="accordion" id="accordionAccident" data-toggle-hover="true">
                                              <div class="collapse-margin">
                                                <div class="card-header collapsed" id="headingOne" data-toggle="collapse" role="button" data-target="#collapseOneAccident" aria-expanded="false" aria-controls="collapseOneEngineering">
                                                  <span class="lead collapse-title font-small-3">
                                                    <code> Risk Upper Text </code>
                                                  </span>
                                                </div>

                                                <div id="collapseOneAccident" class="collapse" aria-labelledby="headingOne" data-parent="#accordionAccident" style="">
                                                  <div class="card-body">
                                                    <div id="accident_schedule" name="accident_schedule" class="form-control" style="overflow:scroll;height:300px;max-height:300px" contenteditable="true">
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="collapse-margin">
                                                  <div class="card-header" id="headingTwo" data-toggle="collapse" role="button" data-target="#collapseTwoAccident" aria-expanded="false" aria-controls="collapseTwoEngineering">
                                                    <span class="lead collapse-title font-small-3">
                                                      <code> Risk Lower Text </code>
                                                    </span>
                                                  </div>
                                                  <div id="collapseTwoAccident" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionAccident">
                                                    <div class="card-body">

                                                      <div id="accident_beneficiary" name="accident_beneficiary" class="form-control" style="overflow:scroll;height:300px;max-height:300px" contenteditable="true">
                                                      </div>
                                                    </div>
                                                  </div>


                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                  </section>







                                  <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">

                                    <input type="hidden" name="accidentkey" id="accidentkey" value="{{ Request::old('accidentkey') ?: '' }}">
                                    <button class="btn btn-sm btn-primary rounded pull-left waves-effect waves-light" id="btnaccident" name="btnaccident" onclick="addAccidentDetails()" type="button">+ Click to Add </button>

                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="text-info" id="add_up_accident" name="add_up_accident" value="Add Up Accident" data-toggle="tooltip" data-placement="top" title="" data-original-title="Checking this box will cause Phrontlyne to add up the selected UOM to Sum Insured" checked>

                                  </div>

                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
                    <!-- // Basic Floating Label Form section end -->

                  </div>
                </div>
              </div>

              <div class="collapse-margin">
                <div class="card-header" id="accordionAccidentheadingOne" data-toggle="collapse" role="button" data-target="#accordionAccidentcollapseOne" aria-expanded="false" aria-controls="accordionAccidentcollapseOne">

                </div>

                <div id="accordionAccidentcollapseOne" class="collapse show" aria-labelledby="accordionAccidentheadingOne" data-parent="#accordionAccident">
                  <div class="card-body">
                    <div class="panel-body">
                      <div class="table-responsive">
                        <table id="accidentScheduleTable" class="table table-striped table-hover mb-0 font-small-2 text-center">
                          <thead>
                            <tr>

                              <th>Risk Number</th>
                              <th>Item Number</th>
                              <th>Description</th>
                              <th>UOM</th>
                              <th>Sum Insured</th>
                              <th>Rate</th>
                              <th>Premium</th>
                              <th>Created By</th>
                              <th>Created On</th>

                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>

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
  </section>
  <!-- Accordion with margin end -->
</div>



<div id="funeral_form" name="funeral_form">
  <section id="accordion-with-margin">
    <div class="row">
      <div class="col-sm-12">

        <div class="card collapse-icon accordion-icon-rotate">

          <div class="card-body">

            <div class="accordion" id="accordionFuneral">
              <div class="collapse-margin">
                <div class="card-header" id="accordionFuneralheadingTwo" data-target="#accordionFuneralcollapseTwo" aria-expanded="false" aria-controls="accordionFuneralcollapseTwo">

                </div>

                <div id="accordionFuneralcollapseTwo" class="collapse show" aria-labelledby="accordionFuneralheadingTwo" data-parent="#accordionFuneral">
                  <div class="card-body">
                    <!-- // Basic multiple Column Form section start -->
                    <section id="multiple-column-form">
                      <div class="row match-height">
                        <div class="col-12">

                          <div class="card">

                            <div class="card-content">
                              <div class="card-body">

                                <div class="form-body">
                                  <div class="row">

                                    {{-- <div class="col-md-2 col-12">
                                                                            <div class="form-group{{ $errors->has('funeral_member_type') ? ' has-error' : ''}}">
                                    <label for="first-name-column">Member(s)</label>

                                    <select id="funeral_member_type" name="funeral_member_type" onchange="loadMemberAge();computeFuneralPremium();" rows="3" tabindex="1" data-placeholder="Select here.." class="form-control mb">
                                      <option value="">-- Select coverage --</option>
                                      @foreach($engineeringuom as $unit)
                                      <option value="{{ $unit->type }}">{{ $unit->type }}
                                      </option>
                                      @endforeach
                                    </select>
                                    @if ($errors->has('funeral_member_type'))
                                    <span class="help-block">{{
                                                              $errors->first('funeral_member_type') }}</span>
                                    @endif

                                  </div>
                                </div> --}}


                                <div class="col-md-2 col-12">
                                  <label>Name of Member </label>
                                  <input type="text" value="" class="form-control" id="funeral_member_name" value="{{ Request::old('funeral_member_name') ?: '' }}" name="funeral_member_name">
                                  @if ($errors->has('funeral_member_name'))
                                  <span class="help-block">{{
                                                            $errors->first('funeral_member_name') }}</span>
                                  @endif
                                </div>

                                <div class="col-md-2 col-12">
                                  <label>Cover Amount </label>
                                  <input type="text" min="0" value="0" step="0.01" value="0" onblur="computeFuneralPremium()" class="form-control" id="funeral_benefit" value="{{ Request::old('funeral_benefit') ?: '' }}" name="funeral_benefit">
                                  @if ($errors->has('funeral_benefit'))
                                  <span class="help-block">{{ $errors->first('funeral_benefit')
                                                            }}</span>
                                  @endif
                                </div>

                                <div class="col-md-2 col-12">
                                  <div class="form-group{{ $errors->has('funeral_member_age') ? ' has-error' : ''}}">
                                    <label>Age of Member</label>
                                    <select id="funeral_member_age" name="funeral_member_age" onchange="computeFuneralPremium()" rows="3" tabindex="1" data-placeholder="Select here.." class="form-control mb">
                                      <option value=""></option>
                                      {{-- @foreach($engineeringuom as $unit)
                                                              <option value="{{ $unit->type }}">{{ $unit->type }}
                                      </option>
                                      @endforeach --}}
                                    </select>
                                    @if ($errors->has('funeral_member_age'))
                                    <span class="help-block">{{
                                                              $errors->first('funeral_member_age') }}</span>
                                    @endif
                                  </div>
                                </div>

                                <div class="col-md-2 col-12">
                                  <div class="form-group{{ $errors->has('engineering_unit') ? ' has-error' : ''}}">
                                    <label>Gender</label>
                                    <select id="funeral_member_gender" name="funeral_member_gender" onchange="computeFuneralPremium()" rows="3" tabindex="1" data-placeholder="Select here.." class="form-control mb">
                                      <option value=""></option>
                                      <option value="Male">Male</option>
                                      <option value="Female">Female</option>
                                    </select>
                                    @if ($errors->has('funeral_member_gender'))
                                    <span class="help-block">{{
                                                              $errors->first('funeral_member_gender') }}</span>
                                    @endif
                                  </div>
                                </div>

                                <div class="col-md-2 col-12">
                                  <label>Premium </label>
                                  <input type="text" min="0" value="0" step="0.01" value="0" class="form-control" id="funeral_premium" value="{{ Request::old('funeral_premium') ?: '' }}" name="funeral_premium">
                                  @if ($errors->has('funeral_benefit'))
                                  <span class="help-block">{{ $errors->first('funeral_benefit')
                                                            }}</span>
                                  @endif
                                </div>
                              </div>


                              <div class="col-sm-2">
                                <label>. </label>
                                <div class="input-group">
                                  {{-- <input type="number" min="0" value="0" step="0.01"
                                                            value="0" class="form-control" id="liability_rate"
                                                            value="{{ Request::old('liability_rate') ?: '' }}"
                                  name="liability_rate"> --}}

                                  {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox"
                                                            class="text-info" id="add_up_engineering"
                                                            name="add_up_engineering" value="Add Up Engineering"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Checking this box will cause Phrontlyne to add up the selected UOM to Sum Insured"
                                                            checked>  --}}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">

                      <input type="hidden" name="funeralkey" id="funeralkey" value="{{ Request::old('funeralkey') ?: '' }}">
                      <button class="btn btn-sm btn-primary rounded pull-left waves-effect waves-light" id="btnfuneral" name="btnfuneral" onclick="addFuneralDetails()" type="button">+ Click to Add </button>

                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="text-info" id="add_up_funeral" name="add_up_accident" value="Add Up Funeral" data-toggle="tooltip" data-placement="top" title="" data-original-title="Checking this box will cause Phrontlyne to add up the selected UOM to Sum Insured" checked>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="collapse-margin">
      <div class="card-header" id="accordionFuneralheadingOne" data-toggle="collapse" role="button" data-target="#accordionLiabilitycollapseOne" aria-expanded="false" aria-controls="accordionLiabilitycollapseOne">

      </div>

      <div id="accordionFuneralcollapseOne" class="collapse show" aria-labelledby="accordionFuneralheadingOne" data-parent="#accordionFuneral">
        <div class="card-body">
          <div class="panel-body">
            <div class="table-responsive">
              <table id="funeralScheduleTable" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover mb-0 font-small-2 text-center" width="100%">
                <thead>
                  <tr>




                    <th>Member</th>
                    <th>Name</th>
                    <th>Cover Amount</th>
                    <th>Age</th>
                    <th>Premium</th>
                    <th>Created By</th>
                    <th>Created On</th>


                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
  </section>
</div>

<div id="liabilityinsurance" name="liabilityinsurance">
  <section id="accordion-with-margin">
    <div class="row">
      <div class="col-sm-12">

        <div class="card collapse-icon accordion-icon-rotate">

          <div class="card-body">

            <div class="accordion" id="accordionLiability">

              <div class="collapse-margin">
                <div class="card-header" id="accordionLiabilityheadingTwo" data-toggle="collapse" role="button" data-target="#accordionLiabilitycollapseTwo" aria-expanded="false" aria-controls="accordionLiabilitycollapseTwo">

                </div>
                <div id="accordionLiabilitycollapseTwo" class="collapse show" aria-labelledby="accordionLiabilityheadingTwo" data-parent="#accordionLiability">
                  <div class="card-body">
                    <!-- // Basic multiple Column Form section start -->
                    <section id="multiple-column-form">
                      <div class="row match-height">
                        <div class="col-12">

                          <div class="card">

                            <div class="card-content">
                              <div class="card-body">

                                <div class="form-body">



                                  <div class="row">

                                    <div class="col-md-2 col-12">
                                      <label for="first-name-column">Risk Type</label>
                                      <div class="form-label-group">
                                        <select id="liability_risk_type" name="liability_risk_type" onchange="getCommissionRates(this);" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                          <option value="">-- Select coverage --</option>
                                          @foreach($producttypes as $producttype)
                                          <option value="{{ $producttype->type }}"> {{$producttype->type }}</option>
                                          @endforeach
                                        </select>
                                        <label for="first-name-column">Risk Type</label>
                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Unit of Measure</label>
                                      <div class="form-label-group">
                                        <select id="liability_unit" name="liability_unit" rows="3" tabindex="1" data-placeholder="Select here Unit of Measure (UOM)" style="width:100%">
                                          <option value="">-- Select UOM --</option>
                                          @foreach($limitsofmeasures as $unit)
                                          <option value="{{ $unit->type }}">{{ $unit->type }}</option>
                                          @endforeach
                                        </select>

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Risk</label>
                                      <div class="form-label-group">
                                        <input type="number" min="1" value="1" step="1" class="form-control" id="liability_risk_number" value="{{ Request::old('liability_risk_number') ?: '' }}" name="accident_risk_number">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Item</label>
                                      <div class="form-label-group">
                                        <input type="number" min="1" value="1" step="1" class="form-control" id="liability_item_number" value="{{ Request::old('liability_item_number') ?: '' }}" name="liability_item_number">

                                      </div>
                                    </div>


                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Sum Assured </label>
                                      <div class="form-label-group">
                                        <input type="text" min="0" value="0" step="0.01" value="0" class="form-control" id="liability_si" value="{{ Request::old('liability_si') ?: '' }}" name="liability_si">

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="company-column">Investment Premium</label>
                                      <div class="form-label-group">
                                        <input type="number" min="0" value="0" step="0.01" value="0" class="form-control" id="liability_rate" value="{{ Request::old('liability_rate') ?: '' }}" name="liability_rate">

                                      </div>
                                    </div>
                                  </div>












                                  <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">

                                    <input type="hidden" name="liabilitykey" id="liabilitykey" value="{{ Request::old('liabilitykey') ?: '' }}">
                                    <button class="btn btn-sm btn-primary rounded pull-left waves-effect waves-light" id="liabilitybutton" name="liabilitybutton" onclick="addLiabilityDetails()" type="button">+ Click to Add </button>
                                    </span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="text-info" id="add_up_liability" name="add_up_liability" value="Add Up Liability" data-toggle="tooltip" data-placement="top" title="" data-original-title="Checking this box will cause Phrontlyne to add up the selected UOM to Sum Insured" checked>

                                  </div>

                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
                    <!-- // Basic Floating Label Form section end -->

                  </div>
                </div>
              </div>

              <div class="collapse-margin">
                <div class="card-header" id="accordionLiabilityheadingOne" data-toggle="collapse" role="button" data-target="#accordionLiabilitycollapseOne" aria-expanded="false" aria-controls="accordionLiabilitycollapseOne">

                </div>

                <div id="accordionLiabilitycollapseOne" class="collapse show" aria-labelledby="accordionLiabilityheadingOne" data-parent="#accordionLiability">
                  <div class="card-body">
                    <div class="panel-body">
                      <div class="table-responsive">
                        <table id="liabilityScheduleTable" class="table table-striped table-hover mb-0 font-small-2 text-center">
                          <thead>
                            <tr>

                              <th>Risk Number</th>
                              <th>Item Number</th>
                              <th>Description</th>
                              <th>UOM</th>
                              <th>Sum Insured</th>
                              <th>Rate</th>
                              <th>Premium</th>
                              <th>Created By</th>
                              <th>Created On</th>

                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>

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
  </section>
  <!-- Accordion with margin end -->
</div>

<div class="card shadow-lg">
  <div class="card-content">
    <div class="card-body">
      <div class="wrapper">

        <hr class="border-b border-gray-300 border-dotted border-4">

        <h1 class="text-center text-3xl m-4 font-bold">General Information</h1>

        <div class="row">
          <div class="col-md-4 form-single-div">
               {{-- {{$currencies}} --}}
            <label for="quotation_number">Quotation number</label>
            <input type="number" class="form-control block-mask text-uppercase" id="quotation_number" name="quotation_number" onchange="checkPolicyProduct();recomputePremiums();recomputeNonMotorPremiums();loadProductCoverTypes();" required>
         </div>
         {{-- @foreach($producttypes as $policy)
             <option value="{{ $policy->type }}">{{ $policy->type }}</option>
         @endforeach --}}
           {{-- <select id="quotation_number" class="custom-select form-control"
                                  name="quotation_number" data-required="true" style="width: 100%;" onchange="checkPolicyProduct();recomputePremiums();recomputeNonMotorPremiums();loadProductCoverTypes();"
                                  data-placeholder="Select here.."  required>
                                      @foreach($producttypes as $policy)
                                  <option value="{{ $policy->type }}">{{ $policy->type }}</option>
                                      @endforeach
            </select> --}}
          <div class="col-md-4 form-single-div">
            <label for="scheme_name">Scheme name</label>
            <input type="text" class="form-control block-mask text-uppercase" id="scheme_name" name="scheme_name" required>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="new_business_renewal">New Business or Renewal</label>
            <select name="new_business_renewal" id="new_business_renewal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="New Business">New Business</option>
              <option value="Renewal">Renewal</option>
            </select>
            {{-- <input type="number" class="form-control" id="customer_mobile_number" name="customer_mobile_number" required> --}}
          </div>
          <div class="col-md-4"></div>
        </div>
        <div class="row">
          <div class="col-md-4 form-single-div">
            <label for="channel">Channel</label>
            <select name="channel" id="channel" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Direct">Direct</option>
              <option value="Broker">Broker</option>
              <option value="Agency">Agency</option>
            </select>
            {{-- <input type="text" id="channel" name="channel" class="form-control block-mask text-uppercase"> --}}
            <!-- <input type="text" id="channel" name="channel" class="form-control block-mask text-uppercase"> -->
            {{-- <select id="channel" class="custom-select form-control"
                                  name="channel" data-required="true" style="width: 100%;" onchange="recomputePremiums();recomputeNonMotorPremiums();"
                                  data-placeholder="Select here.."  required>
                                
                                  <option value="{{ $currency->type }}">{{ $currency->type }} @ {{ $currency->rate }}</option>

            </select> --}}
          </div>
          <div class="col-md-4 form-single-div">
            <label for="intermediary_name">Intermediary Name</label>
            <input type="text" id="intermediary_name" name="intermediary_name" class="form-control block-mask text-uppercase">
            {{-- <select id="cover_type" class="custom-select form-control"
                              onchange="checkCoverType();"
                                  name="cover_type" data-required="true" style="width: 100%;"
                                  data-placeholder="Select here.."  required>
                              </select> --}}
          </div>
          <div class="col-md-4 form-single-div">
            <label for="quote_date">Quote Date</label>
            <input type="date" id="quote_date" name="quote_date" class="form-control" value="{{old('date')}}">

            {{-- <select id="rating_factor" class="custom-select form-control" onchange="getExpiryDate();"
                                  name="rating_factor" data-required="true" style="width: 100%;"
                                  data-placeholder="Select here.."  required>
                              </select> --}}
          </div>
          {{-- <input type="hidden" name="commence_date" id="commence_date">
                          <input type="hidden" name="expiry_date" id="expiry_date">
                          <input type="hidden" id="policy_number" name="policy_number" value="{{ uniqid(15) }}" readonly="true" class="form-control" rows="3" tabindex="1"> --}}
        </div>
        <div class="row">
          <div class="col-md-4 form-single-div">
            <label for="prepared_by">Prepared by</label>
            <select name="prepared_by" id="prepared_by" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Benedicta Obeng Agyekum">Benedicta Obeng Agyekum</option>
              <option value="Daniel Boakye">Daniel Boakye</option>
              <option value="Christiana Ohenewaa Yeboah">Christiana Ohenewaa Yeboah</option>
              <option value="Dzifa Fiati">Dzifa Fiati</option>
              <option value="Bridget  Wolasi Arku">Bridget Wolasi Arku</option>
              <option value="Sylvester Acheampong">Sylvester Acheampong</option>
            </select>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="checked_by">Checked by</label>
            <select name="checked_by" id="checked_by" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Benedicta Obeng Agyekum">Benedicta Obeng Agyekum</option>
              <option value="Daniel Boakye">Daniel Boakye</option>
              <option value="Christiana Ohenewaa Yeboah">Christiana Ohenewaa Yeboah</option>
              <option value="Dzifa Fiati">Dzifa Fiati</option>
              <option value="Bridget  Wolasi Arku">Bridget Wolasi Arku</option>
              <option value="Sylvester Acheampong">Sylvester Acheampong</option>
            </select>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="approved_by">Approved by</label>
            <select name="approved_by" id="approved_by" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Benedicta Obeng Agyekum ">Benedicta Obeng Agyekum </option>
              <option value="Bridget  Wolasi Arku">Bridget Wolasi Arku </option>
              <option value="Kwame Kensah Twumasi">Kwame Kensah Twumasi</option>
              <option value="Dzifa Fiati">Dzifa Fiati </option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 form-single-div">
            {{-- {{$new_record->expense_loading ?? "Bebelino"}} --}}
            <label for="commencement_date">Commencement Date</label>
            {{-- <p>{{$intermediary}}</p> --}}
            {{-- <p>{{$mandatecompanies}}</p> --}}
             {{-- <p>{{$beneficiaries}}</p> --}}
            <input type="date" id="commencement_date" name="commencement_date" class="form-control" value="{{old('date')}}">
@php

$industries = [
'Administration',
'Agriculture',
'Airline',
'Aluminium Smelting',
'Army',
'Banks',
'Breweries',
'Building industry',
'Building Societies',
'Car dealers',
'Chemicals',
'Civil servants (not teachers and police force)',
'Construction / Drilling Services',
'Dentists',
'Doctors',
'Education',
'Electricians',
'Financial services',
'Food Processing',
'Heavy Manufacturing', 
'Hospitals / Healthcare',
'Hotels',
'Insurance and Insurance Brokers',
'Investment Companies',
'IT Services',
'Legal profession',
'Light Manufacturing',
'Long-Distance Trucking',
'Manufacturing (Paints etc.)',
'Mining Prospecting',
'Motor industry',
'NGO',
'Nurses',
'Oil refinery',
'Opencast Mining', 
'Parastatals',
'Petrol Stations',
'Police',
'Property Companies',
'Public Works (water and sewerage)',
'Regulatory Authority',
'Retailers',
'Teachers',
'Textiles',         
'Timber',
'Tourism other',
'Trade Unions',
'Traffic police',
'Transport & Logistics',
'Travel Agents',
'Underground Mining',
]
@endphp 

            <!-- {{-- <select id="rating_factor" class="custom-select form-control" onchange="getExpiryDate();"
                            name="rating_factor" data-required="true" style="width: 100%;"
                            data-placeholder="Select here.."  required>
                        </select> --}} -->
          </div>
          <div class="col-md-4 form-single-div">
            <label for="industry">Industry</label>
            <select name="industry" id="industry" class="custom-select form-control" required>
              <option> --Select-- </option>
              @foreach($industries as $industry)
                  <option value=`{$industry}`>{{$industry}}</option>
              @endforeach
              <!-- <option value="Tech Industry">Tech industry</option>
              <option value="Cons">Construction</option> -->
            </select>
            {{-- <input type="text" class="form-control block-mask text-uppercase" id="scheme_name" name="scheme_name" required> --}}
          </div>
          <div class="col-md-4 form-single-div">
            <label for="scheme_type">Scheme Type</label>
            <select name="scheme_type" id="scheme_type" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All Causes">All Causes</option>
              <option value="Accidental Causes Only">Accidental Causes Only</option>
              <!-- <option value="">Scheme Type 3</option> -->
            </select>
          </div>
          {{-- <div class="col-auto">
            <label for="scheme-type">Scheme Type</label>
            <div class="form-label-group">
              <select id="scheme_type" name="scheme_type" tabindex="1" value="{{ $new_record->scheme_type ?? ''}}" data-placeholder="Select Scheme Type.." style="width:100%">
                <option value="">-- Select scheme type --</option>
              </select>
            </div>
          </div> --}}
          <!-- {{-- <div class="col-md-4"></div> --}} -->
        </div>
        <div class="row">
          <div class="col-md-4 form-single-div">
            <label for="occupationclass">Occupation class</label>
            <input type="text" id="occupationclass" name="occupationclass" class="form-control block-mask text-uppercase" value="2" readonly>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="numberofmembers">Number of members to be covered</label>
            <input type="number" min="1" name="numberofmembers" id="numberofmembers" class="custom-select form-control" required>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="averageage">Average Age</label>
            <input type="number" min="1" name="averageage" id="averageage" class="custom-select form-control" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 form-single-div">
            <label for="limitofrisk">Limit of risk reference</label>
            <select name="limitofrisk" id="limitofrisk" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Annual Salary">Annual Salary</option>
              <option value="Monthly Salary">Monthly Salary</option>
              <option value="Yearly salary">Yearly salary</option>
            </select>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="currency">Currency</label>
            <select name="currency" id="currency" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="American Dollar($)">American Dollar($)</option>
              <option value="Ghana Cedi(GHS)">Ghana Cedi(GHS)</option>
              <option value="CFA">CFA</option>
            </select>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="exchangerate">Exchange Rate (1 USD in GHS)</label>
            <input type="text" id="exchangerate" name="exchangerate" class="form-control block-mask text-uppercase" value="" >
            <!-- <select name="exchangerate" id="exchangerate" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="American Dollar($)">American Dollar($)</option>
              <option value="Ghana Cedi(GHS)">Ghana Cedi(GHS)</option>
              <option value="CFA">CFA</option>
            </select> -->
          </div>
        </div>
        <!-- <div class="card shadow-lg">
        <div class="card-header">
            General Life Quote
        </div>
        <div class="card-body">
        
           
      </div>
    </div> -->
    <div class="row">
          <div class="col-md-4 form-single-div">
              <label for="totalannualsalary">Total Annual Salary</label>
              <input type="number" id="totalannualsalary" name="totalannualsalary" class="form-control block-mask text-uppercase" value="" >
              <!-- <select name="exchangerate" id="exchangerate" class="custom-select form-control" required>
                <option> --Select-- </option>
                <option value="American Dollar($)">American Dollar($)</option>
                <option value="Ghana Cedi(GHS)">Ghana Cedi(GHS)</option>
                <option value="CFA">CFA</option>
              </select> -->
            </div>
            <div class="col-md-4 form-single-div">
              <label for="amount_to_be_covered_per_person">Amount to be covered per person</label>
              <input type="number" id="amount_to_be_covered_per_person" name="amount_to_be_covered_per_person" class="form-control block-mask text-uppercase" value="" >
              <!-- <select name="exchangerate" id="exchangerate" class="custom-select form-control" required>
                <option> --Select-- </option>
                <option value="American Dollar($)">American Dollar($)</option>
                <option value="Ghana Cedi(GHS)">Ghana Cedi(GHS)</option>
                <option value="CFA">CFA</option>
              </select> -->
            </div>
            <div class="col-md-4 form-single-div">
              <!-- <label for="totalsumassured">Total sum assured(GHS)</label>
              <input type="number" id="totalsumassured" name="totalsumassured" class="form-control block-mask text-uppercase" value="1,541,288.28" readonly> -->
              <!-- <select name="exchangerate" id="exchangerate" class="custom-select form-control" required>
                <option> --Select-- </option>
                <option value="American Dollar($)">American Dollar($)</option>
                <option value="Ghana Cedi(GHS)">Ghana Cedi(GHS)</option>
                <option value="CFA">CFA</option>
              </select> -->
            <label for="totalsumassured">Total Sum Assured(GHS)</label>
            <input type="text" id="totalsumassured" name="totalsumassured" class="form-control block-mask text-uppercase" value="1,541,288.28" readonly>
          </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-single-div">
                  <label for="scenario">Scenario</label>
                  <!-- <input type="number" id="scenario" name="scenario" class="form-control block-mask text-uppercase" value="" > -->
                  <select name="scenario" id="scenario" class="custom-select form-control" required>
                    <option> --Select-- </option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                  </select>
            </div>
          </div>
        </div>
        <hr class="border-b border-gray-300 border-dotted border-4">

        <h1 class="text-center text-3xl m-4 font-bold">Loadings</h1>
        <!-- Loadings -->
        <div class="row">
          <div class="col-md-4 form-single-div">
            {{-- <p>{{$currencies}}</p> --}}
            <label for="commission_rate">Commission rate</label>
            <input type="number" min="1" value="{{ $new_record->commission_rate ?? ''}}" class="form-control block-mask text-uppercase" id="commission_rate" name="commission_rate" required>
            

            {{-- <select id="quotation_number" class="custom-select form-control"
                                  name="quotation_number" data-required="true" style="width: 100%;" onchange="checkPolicyProduct();recomputePremiums();recomputeNonMotorPremiums();loadProductCoverTypes();"
                                  data-placeholder="Select here.."  required>
                                      @foreach($producttypes as $policy)
                                  <option value="{{ $policy->type }}">{{ $policy->type }}</option>
                                     @endforeach
            </select> --}}
          </div>
          <div class="col-md-4 form-single-div">
            <label for="profit_loading">Profit Loading</label>
            <input type="number" value="{{ $new_record->profit_loading ?? ''}}" min="1" class="form-control block-mask" id="profit_loading" name="profit_loading" required>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="expense_loading">Expense Loading</label>
            <input type="number" min="1" value="{{ $new_record->expense_loading ?? ''}}" class="form-control block-mask" id="expense_loading" name="expense_loading" required>
            <!-- <label for="new_business_renewal">New Business or Renewal</label>
            <select name="new_business_renewal" id="new_business_renewal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="New Business">New Business</option>
              <option value="Renewal">Renewal</option>
            </select> -->
            {{-- <input type="number" class="form-control" id="customer_mobile_number" name="customer_mobile_number" required> --}}
          </div>
          <div class="col-md-4"></div>
        </div>

        <hr class="border-b border-gray-300 border-dotted border-4">

        <h1 class="text-center text-3xl m-4 font-bold">Group Life Assurance</h1>

        <div class="row">
          <div class="col-md-4 form-single-div">
            <label for="multipleofsalary">Multiple of Salary</label>
            <select name="scenario" id="scenario" class="custom-select form-control" required>
                    <option> --Select-- </option>
                    <option value="1">1</option>
                    <option value="1.5">1.5</option>
                    <option value="2">2</option>
                    <option value="2.5">2.5</option>
                    <option value="3">3</option>
                    <option value="3.5">3.5</option>
                    <option value="4">4</option>
                    <option value="4.5">4.5</option>
                    <option value="5">5</option>
                    <option value="5.5">5.5</option>
                    <option value="6">6</option>
            </select>
            {{-- <select name="scenario" id="scenario" class="custom-select form-control" required>
              @for($i = 1; $i < 6; $i++) 
                @for ($j = 0; $j <= 1; $j += 0.5)
                    <option value="{{ $i + $j }}">{{ $i + $j }}</option> 
                @endfor 
              @endfor          <!-- <input type="number" class="form-control block-mask text-uppercase" id="multipleofsalary" name="multipleofsalary" required> --> --}}


            {{-- <select id="quotation_number" class="custom-select form-control"
                                  name="quotation_number" data-required="true" style="width: 100%;" onchange="checkPolicyProduct();recomputePremiums();recomputeNonMotorPremiums();loadProductCoverTypes();"
                                  data-placeholder="Select here.."  required>
                                      @foreach($producttypes as $policy)
                                  <option value="{{ $policy->type }}">{{ $policy->type }}</option>
            @endforeach
            </select> --}}
          </div>
          <div class="col-md-4 form-single-div">
            <label for="currentFCL">Current FCL</label>
            <input type="number" class="form-control block-mask text-uppercase" id="currentFCL" name="currentFCL" required>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="totalsumassured">Normal Retirement Age</label>
            <input type="number" id="normalretirementage" name="normalretirementage" class="form-control block-mask text-uppercase" value="60" readonly>
            <!-- <label for="normalretirementage">Normal Retirement Age</label>
            <input type="number" class="form-control block-mask text-uppercase" id="normalretirementage" name="normalretirementage" required> -->
            <!-- <label for="new_business_renewal">New Business or Renewal</label>
            <select name="new_business_renewal" id="new_business_renewal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="New Business">New Business</option>
              <option value="Renewal">Renewal</option>
            </select> -->
            {{-- <input type="number" class="form-control" id="customer_mobile_number" name="customer_mobile_number" required> --}}
          </div>
          <div class="col-md-4 form-single-div">
            <label for="coverterminationage">Cover Termination Age</label>
            <input type="number" class="form-control block-mask text-uppercase" id="coverterminationage" name="coverterminationage" value="70" readonly>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="claimsexperience">Claims Experience</label>
            <input type="text" class="form-control block-mask text-uppercase" id="claimsexperience" name="claimsexperience" value="NO" readonly>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="discount">Discount(%)</label>
            <input type="number" class="form-control block-mask text-uppercase" id="discount" name="discount" value="25.00%">
          </div>
          <div class="col-md-4"></div>
        </div>

        <hr class="border-b border-gray-300 border-dotted border-4">

        <h1 class="text-center text-3xl m-4 font-bold">Critical Illness</h1>

        <div class="row">
          <div class="col-md-4 form-single-div">
            <label for="percentageofGLA">CI as a percentage of GLA Benefit(%)</label>
            <input type="number" id="percentageofGLA" name="percentageofGLA" class="form-control block-mask text-uppercase" value="50%" >
          </div>
          <div class="col-md-4 form-single-div">
            <label for="maxCIbenefit">Maximum CI benefit</label>
            <input type="number" id="maxCIbenefit" name="maxCIbenefit" class="form-control block-mask text-uppercase" value="1000000" readonly>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="producttype">Product Type</label>
            <select name="producttype" id="producttype" class="custom-select form-control" required>
                <option> --Select-- </option>
                <option value="Stand alone">Stand alone</option>
                <option value="Accelerated">Accelerated</option>
            </select>
          </div>
          <div class="col-md-4 form-single-div">
            <label for="coverterminationage">Cover Termination Age</label>
            <input type="number" id="coverterminationage" name="coverterminationage" class="form-control block-mask text-uppercase" value="70" readonly>
          </div>
        </div>

       <hr class="border-b border-gray-300 border-dotted border-4">

       <h1 class="text-center text-3xl m-4 font-bold">Permanent and Total Disability</h1>

       {{-- Permanent and Total Disability --}}

       <div class="row">
        <div class="col-md-4 form-single-div">
          <label for="covertype">Cover Type</label>
          <select name="covertype" id="covertype" class="custom-select form-control" required>
            <option> --Select-- </option>
            <option value="Accidental Causes Only">Accidental Causes Only</option>
            <option value="All Causes">All Causes</option>
          </select>

          {{-- <input type="number" id="covertype" name="covertype" class="form-control block-mask text-uppercase" value="50%" > --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="multipleofsalary">Multiple of Salary</label>
          <select name="multipleofsalary" id="multipleofsalary" class="custom-select form-control" required>
            <option> --Select-- </option>
            <option value="1">1</option>
            <option value="1.5">1.5</option>
            <option value="2">2</option>
            <option value="2.5">2.5</option>
            <option value="3">3</option>
            <option value="3.5">3.5</option>
            <option value="4">4</option>
            <option value="4.5">4.5</option>
            <option value="5">5</option>
            <option value="5.5">5.5</option>
            <option value="6">6</option>
          </select>
          {{-- <input type="number" id="multipleofsalary" name="multipleofsalary" class="form-control block-mask text-uppercase" value="1000000" readonly> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="producttype">Product Type</label>
          <select name="producttype" id="producttypePTD" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Stand alone">Stand alone</option>
              <option value="Accelerated">Accelerated</option>
          </select>
        </div>
        <div class="col-md-4 form-single-div">
          <label for="coverterminationage">Cover Termination Age</label>
          <input type="number" id="coverterminationage" name="coverterminationage" class="form-control block-mask text-uppercase" value="65" readonly>
        </div>
        <div class="col-md-4 form-single-div">
          <label for="deferredperiod">Deferred Period</label>
          <input type="text" id="deferredperiod" name="deferredperiod" class="form-control block-mask text-uppercase" value="" >
        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
        <div class="col-md-4 form-single-div">
          <label for="coverterminationage">Disability Definition</label>
          <input type="text" id="disabilitydefinition" name="disabilitydefinition" class="form-control block-mask text-uppercase" value="" >
        </div>
      </div>

      <hr class="border-b border-gray-300 border-dotted border-4">

      <h1 class="text-center text-3xl m-4 font-bold">Total and Temporary Disability</h1>

      <div class="row">
        <div class="col-md-4 form-single-div">
          <label for="covertype">Cover Type</label>
          <select name="covertype" id="covertype" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select>
        </div>
        <div class="col-md-4 form-single-div">
          <label for="weeklybenefit">Weekly Benefit</label>
          <input type="number" id="weeklybenefit" name="weeklybenefit" class="form-control block-mask text-uppercase" value="52" required>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="weeklybenefit">Maximum number of payments</label>
          <input type="number" id="maxnumofpayments" name="maxnumofpayments" class="form-control block-mask text-uppercase" value="6" readonly>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="coverterminationage">Cover Termination Age</label>
          <input type="number" id="coverterminationage" name="coverterminationage" class="form-control block-mask text-uppercase" value="65" readonly>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="deferredperiod">Deferred Period</label>
          <input type="text" id="deferredperiod" name="deferredperiod" class="form-control block-mask text-uppercase" value="" >
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="disabilitydefinition">Disability Definition</label>
          <input type="text" id="disabilitydefinition" name="disabilitydefinition" class="form-control block-mask text-uppercase" value="" >
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>z                                 
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
      </div>

      <hr class="border-b border-gray-300 border-dotted border-4">

      <h1 class="text-center text-3xl m-4 font-bold">Spouses Group Life Assurance(SGLA)</h1>

      <div class="row">
        <div class="col-md-4 form-single-div">
          <label for="percentageofannualsalary">SGA as a percentage of annual salary(%)</label>
          <input type="number" id="percentageofannualsalary" name="percentageofannualsalary" class="form-control block-mask text-uppercase" value="50">
        </div>
        <div class="col-md-4 form-single-div">
          <label for="maximumbenefit">Maximum Benefit(GHS)</label>
          <input type="number" id="maximumbenefit" name="maximumbenefit" class="form-control block-mask text-uppercase" value="500000">
        </div>
        <div class="col-md-4 form-single-div">
          <label for="coverterminationage">Cover Termination Age</label>
          <input type="number" id="coverterminationage" name="coverterminationage" class="form-control block-mask text-uppercase" value="70">
        </div>
      </div>

      <hr class="border-b border-gray-300 border-dotted border-4">

      <h1 class="text-center text-3xl m-4 font-bold">Complimentary Benefits</h1>

      <div class="row">
        <div class="col-md-4 form-single-div">
          <label for="spousalbenefit">Spousal Benefit</label>
          <select name="spousalbenefit" id="spousalbenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select>
        </div>
        <div class="col-md-4 form-single-div">
          <label for="spousal">Spouse</label>
          <input type="number" id="spousal" name="spousal" class="form-control block-mask text-uppercase" value="" required>
          {{-- <select name="spousal" id="spousal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="numberofspouse">Number of spouse</label>
          <input type="number" id="numberofspouse" name="numberofspouse" class="form-control block-mask text-uppercase" value="" required>
          {{-- <select name="spousal" id="spousal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="children">Children</label>
          <input type="number" id="children" name="children" class="form-control block-mask text-uppercase" value="" required>
          {{-- <select name="spousal" id="spousal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="numberofchildren">Number of Children</label>
          <input type="number" id="numberofchildren" name="numberofchildren" class="form-control block-mask text-uppercase" value="" required>
          {{-- <select name="spousal" id="spousal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="funeralexpense">Funeral Expense Benefit</label>
          <select name="funeralexpense" id="funeralexpense" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select>
        </div>
        <div class="col-md-4 form-single-div">
          <label for="amountpermember">Amount per member</label>
          <input type="number" id="amountpermember" name="amountpermember" class="form-control block-mask text-uppercase" value="" required>
          {{-- <select name="spousal" id="spousal" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select> --}}
        </div>
      </div>

      <hr class="border-b border-gray-300 border-dotted border-4">

      <h1 class="text-center text-3xl m-4 font-bold">Group Personal Accident Benefits(GPA)</h1>

      <div class="row">
        <div class="col-md-4 form-single-div">
          <label for="multipleofsalary">Multiple of Salary</label>
          <select name="multipleofsalary" id="multipleofsalary" class="custom-select form-control" required>
            <option> --Select-- </option>
            <option value="1">1</option>
            <option value="1.5">1.5</option>
            <option value="2">2</option>
            <option value="2.5">2.5</option>
            <option value="3">3</option>
            <option value="3.5">3.5</option>
            <option value="4">4</option>
            <option value="4.5">4.5</option>
            <option value="5">5</option>
            <option value="5.5">5.5</option>
            <option value="6">6</option>
          </select>
        </div>
        <div class="col-md-4 form-single-div">
          <label for="accidentalpermnent">Accidental Permnent (TPD) SA (GHS)</label>
          <input type="number" id="accidentalpermnent" name="accidentalpermnent" class="form-control block-mask text-uppercase" value="4623864.84" readonly>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="accidentaltemporal">Accidental Temporal Disability (TTD) Rate SA (GHS)</label>
          <input type="number" id="accidentaltemporal" name="accidentaltemporal" class="form-control block-mask text-uppercase" value="1541288.28" readonly>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="annualmedicalsum">Annual Medical Sum Assured per Member (% of TPD cover)</label>
          <input type="number" id="annualmedicalsum" name="annualmedicalsum" class="form-control block-mask text-uppercase" value="10.00%" readonly>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="medical_expense_sum_assured">Medical Expense Sum Assured </label>
          <input type="number" id="medical_expense_sum_assured" name="medical_expense_sum_assured" class="form-control block-mask text-uppercase" value="462386.484" readonly>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="discountrate">Discount rate</label>
          <input type="number" id="discountrate" name="discountrate" class="form-control block-mask text-uppercase" value="10.00%" >
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
      </div>

      <hr class="border-b border-gray-300 border-dotted border-4">

      <h1 class="text-center text-3xl m-4 font-bold">Workmen's Compensation Benefits (WC)</h1>

      <div class="row">
        <div class="col-md-4 form-single-div">
          <label for="workmans_compensation">Workmen's Compensation SA (GHS)</label>
          <input type="number" id="workmans_compensation" name="workmans_compensation" class="form-control block-mask text-uppercase" value="1541288.28" readonly>
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
        <div class="col-md-4 form-single-div">
          <label for="wc_endorsement">WC Endorsement</label>
          <select name="wc_endorsement" id="wc_endorsement" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
          </select>
        </div>
        <div class="col-md-4 form-single-div">
          <label for="discountrate">Discount rate</label>
          <input type="number" id="discountrate" name="discountrate" class="form-control block-mask text-uppercase" value="10.00%" >
          {{-- <select name="weeklybenefit" id="weeklybenefit" class="custom-select form-control" required>
              <option> --Select-- </option>
              <option value="All causes">All causes</option>
              <option value="Accidental causes only">Accidental causes only</option>
          </select> --}}
        </div>
      </div>

      <hr class="border-b border-gray-300 border-dotted border-4">

      <h1 class="text-center text-3xl m-4 font-bold">Hospital Cash (HC)</h1>

      <div class="row">
        <div class="col-md-4 form-single-div">
          <label for="amount_payable_per_night">Amount payable per night (GHS)</label>
          <input type="number" id="amount_payable_per_night" name="amount_payable_per_night" class="form-control block-mask text-uppercase" value="100" >
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
</div>
{{-- <div id="mortgage_protection" name="mortgage_protection">
    <div id="general_information">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingEleven" data-toggle="collapse" role="button" data-target="#collapseElevenAccident" aria-expanded="false" aria-controls="collapseElevenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> General Information</code>
                    </span>
                </div>

                <div id="collapseElevenAccident" class="collapse" aria-labelledby="headingEleven" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">
                                

                                <div class="col-md-3">
                                    <label for="number-of-members-to-be-covered">Number of members to be
                                        covered</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" class="form-control" id="number_of_members_to_be_covered" value="{{ $new_record->number_of_members_to_be_covered ?? ''}}" name="number_of_members_to_be_covered">
</div>
</div>

<div class="col-md-3">
  <label for="retirement-age">Retirement Age</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="retirement_age" value="{{ $new_record->retirement_age ?? ''}}" name="retirement_age">
  </div>
</div>

<div class="col-md-3">
  <label for="date-of-birth">Date of Birth</label>
  <div class="form-label-group">
    <input type="date" min="1" class="form-control" id="date_of_birth" value="{{ $new_record->date_of_birth ?? ''}}" name="date_of_birth">
  </div>
</div>

<div class="col-md-3">
  <label for="age-next-birthday">Age Next Birthday</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="age_next_birthday" value="{{ $new_record->age_next_birthday ?? ''}}" name="age_next_birthday">
  </div>
</div>

<div class="col-md-3">
  <label for="loan-term">Loan Term</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="loan_term" value="{{ $new_record->loan_term ?? ''}}" name="loan_term">
  </div>
</div>

<div class="col-md-3">
  <label for="loan-amount">Loan Amount</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="loan_amount" value="{{ $new_record->loan_amount ?? ''}}" name="loan_amount">
  </div>
</div>

<div class="col-md-3">
  <label for="total-sum-assured">Total sum assured</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="total_sum_assured" value="{{ $new_record->total_sum_assured ?? ''}}" name="total_sum_assured">
  </div>
</div>

<div class="col-md-3">
  <label for="scenario">Scenario</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="scenario" value="{{ $new_record->scenario ?? ''}}" name="scenario">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--</div>-->
</div>

</div>
<div id="loadings">
  <div class="collapse-margin">
    <div class="panel panel-warning">
      <div class="card-header collapsed" id="headingTwelve" data-toggle="collapse" role="button" data-target="#collapseTwelveAccident" aria-expanded="false" aria-controls="collapseTwelveAccident">
        <span class="lead collapse-title font-small-3">
          <code> Loadings </code>
        </span>
      </div>

      <div id="collapseTwelveAccident" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordionAccident">
        <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
        <div class="panel-body text-sm">
          <div class="container">
            <div class="row">

              <div class="col-md-3 col-12">
                <label for="commission-rate">Commission Rate</label>
                <div class="form-label-group">
                  <input type="number" min="1" class="form-control" id="commission_rate" value="{{ $new_record->commission_rate ?? ''}}" name="commission_rate">
                </div>
              </div>

              <div class="col-md-3 col-12">
                <label for="profit-loading">Profit Loading</label>
                <div class="form-label-group">
                  <input type="number" min="1" class="form-control" id="profit_loading" name="profit_loading" value="{{ $new_record->profit_loading ?? ''}}">
                </div>
              </div>

              <div class="col-md-3 col-12">
                <label for="expense-loading">Expense loading</label>
                <div class="form-label-group">
                  <input type="number" min="1" class="form-control" id="expense_loading" name="expense_loading" value="{{ $new_record->expense_loading ?? ''}}">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--</div>-->
  </div>

</div>
</div> --}}


{{-- <div id="group_term_life" name="group_term_life"> --}}

{{-- <div id="general_information">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingEleven" data-toggle="collapse" role="button" data-target="#collapseElevenAccident" aria-expanded="true" aria-controls="collapseElevenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code>General Information</code>
                    </span>
                </div>

                <div id="collapseElevenAccident" class="collapse show" aria-labelledby="headingEleven" data-parent="#accordionAccident">
                    <div class="panel-body text-sm">
                        <div class="w-full" >
                            <div class="row w-full p-2">
                                <!-- Commencement Date -->
                                <div class="col-auto">
                                    <label for="commencement-date">Commencement Date</label>
                                    <div class="form-label-group">
                                        <input type="date" min="1" class="form-control" id="commencement_date" value="{{ $new_record->commencement_date ?? ''}}" name="commencement_date">
</div>
</div>

<!-- Industry -->
<div class="col-auto">
  <label for="industry">Industry</label>
  <div class="form-label-group">
    <select id="industry" name="industry" tabindex="1" data-placeholder="Select Industry.." value="{{ $new_record->industry ?? ''}}" style="width:100%">
      <option value="">-- Select industry --</option>
    </select>
  </div>
</div>

<!-- Scheme Type -->
<div class="col-auto">
  <label for="scheme-type">Scheme Type</label>
  <div class="form-label-group">
    <select id="scheme_type" name="scheme_type" tabindex="1" value="{{ $new_record->scheme_type ?? ''}}" data-placeholder="Select Scheme Type.." style="width:100%">
      <option value="">-- Select scheme type --</option>
    </select>
  </div>
</div>




<!-- Number of Members to be Covered -->
<div class="col-auto">
  <label for="number-of-members-to-be-covered">Number of Members to be Covered</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" value="{{ $new_record->number_of_members_to_be_covered ?? ''}}" id="number_of_members_to_be_covered" name="number_of_members_to_be_covered">
  </div>
</div>

<!-- Average Age -->
<div class="col-auto">
  <label for="average-age">Average Age</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="average_age" value="{{ $new_record->average_age ?? ''}}" name="average_age">
  </div>
</div>

<!-- Limit of Risk Reference -->
<div class="col-auto">
  <label for="limit-of-risk-reference">Limit of Risk Reference</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="limit_of_risk_reference" value="{{ $new_record->limit_of_risk_reference ?? ''}}" name="limit_of_risk_reference">
  </div>
</div>

<!-- Total Annual Salary -->
<div class="col-auto">
  <label for="total-annual-salary">Total Annual Salary</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="total_annual_salary" value="{{ $new_record->total_annual_salary ?? ''}}" name="total_annual_salary">
  </div>
</div>

<!-- Amount to be Covered per Person -->
<div class="col-auto">
  <label for="amount-to-be-covered-per-person">Amount to be Covered per Person</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="amount_to_be_covered_per_person" value="{{ $new_record->amount_to_be_covered_per_person ?? ''}}" name="amount_to_be_covered_per_person">
  </div>
</div>

<!-- Total Sum Assured -->
<div class="col-auto">
  <label for="total-sum-assured">Total Sum Assured</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="total_sum_assured" value="{{ $new_record->total_sum_assured ?? ''}}" name="total_sum_assured">
  </div>
</div>

<!-- Scenario -->
<div class="col-auto">
  <label for="scenario">Scenario</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="scenario" value="{{ $new_record->scenario ?? ''}}" name="scenario">
  </div>
</div>

<!-- Add more fields as needed -->

</div>
</div>
</div>
</div>
</div>
</div>
</div> --}}

{{-- <div id="loadings">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingTwelve" data-toggle="collapse" role="button" data-target="#collapseTwelveAccident" aria-expanded="false" aria-controls="collapseTwelveAccident">
                    <span class="lead collapse-title font-small-3">
                        <code>Loadings</code>
                    </span>
                </div>

                <div id="collapseTwelveAccident" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordionAccident">
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                                <!-- Commission Rate -->
                                <div class="col-auto">
                                    <label for="commission-rate">Commission Rate</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" class="form-control" id="commission_rate" value="{{ $new_record->commission_rate ?? ''}}" name="commission_rate">
</div>
</div>

<!-- Profit Loading -->
<div class="col-auto">
  <label for="profit-loading">Profit Loading</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="profit_loading" value="{{ $new_record->profit_loading ?? ''}}" name="profit_loading">
  </div>
</div>

<!-- Expense Loading -->
<div class="col-auto">
  <label for="expense-loading">Expense Loading</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="expense_loading" value="{{ $new_record->expense_loading ?? ''}}" name="expense_loading">
  </div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div> --}}


{{-- <div id="group_life_assurance">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingThirteen" data-toggle="collapse" role="button" data-target="#collapseThirteenAccident" aria-expanded="false" aria-controls="collapseThirteenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code>Group Life Assurance</code>
                    </span>
                </div>

                <div id="collapseThirteenAccident" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordionAccident">
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                                <!-- Multiple of Salary -->
                                <div class="col-auto">
                                    <label for="multiple-of-salary">Multiple of Salary</label>
                                    <div class="form-label-group">
                                        <select id="gla_multiple_of_salary" name="gla_multiple_of_salary" value="{{ $new_record->gla_multiple_of_salary ?? ''}}" tabindex="1" data-placeholder="Select here.." style="width:100%">
<option value="">-- Select multiple of salary --</option>
</select>
<label for="multiple-of-salary">Multiple of Salary</label>
</div>
</div>

<!-- Current FCL -->
<div class="col-auto col-1 welloff">
  <label for="current-fcl">Current FCL</label>
  <div class="form-label-group">
    <input type="number" min="1" value="{{ $new_record->gla_current_fcl ?? ''}}" class="form-control" id="gla_current_fcl" name="gla_current_fcl">
  </div>
</div>

<!-- Normal Retirement Age -->
<div class="col-auto">
  <label for="normal-retirement-age">Normal Retirement Age</label>
  <div class="form-label-group">
    <input type="number" min="1" value="{{ $new_record->gla_normal_retirement_age ?? ''}}" class="form-control" id="gla_normal_retirement_age" name="gla_normal_retirement_age">
  </div>
</div>

<!-- Cover Termination Age -->
<div class="col-auto">
  <label for="cover-termination-age">Cover Termination Age</label>
  <div class="form-label-group">
    <input type="number" min="1" value="{{ $new_record->gla_cover_termination_age ?? ''}}" class="form-control" id="gla_cover_termination_age" name="gla_cover_termination_age">
  </div>
</div>

<!-- Claims Experience -->
<div class="col-auto">
  <label for="claims-experience">Claims Experience</label>
  <div class="form-label-group">
    <input type="text" value="{{ $new_record->gla_claims_experience ?? ''}}" class="form-control" id="gla_claims_experience" name="gla_claims_experience">
  </div>
</div>

<!-- Discount Rate-->
<div class="col-auto wellin">
  <label for="discount-rate">Discount Rate</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gla_discount_rate" value="{{ $new_record->gla_discount_rate ?? ''}}" name="gla_discount_rate">
  </div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div> --}}



{{-- <div id="additional_benefits_to_be_quoted">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingFourteen" data-toggle="collapse" role="button" data-target="#collapseFourteenAccident" aria-expanded="false" aria-controls="collapseFourteenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code>Additional Benefits to be Quoted</code>
                    </span>
                </div>

                <div id="collapseFourteenAccident" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordionAccident">
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- CI Benefit -->
                                    <div class="mb-2">
                                        <input type="checkbox" id="ci-benefit" name="additional-checkbox" value="CI Benefit" onchange="toggleCheck('ci-benefit', 'ci', 'ci_status')">
                                        <input type="text" min="1" value="{{ $new_record->ci_status ?? ''}}" hidden class="form-control" id="ci_status" name="ci_status">
<label for="ci-benefit">CI Benefit</label>
</div>

<!-- PTD Benefit -->
<div class="mb-2">
  <input type="checkbox" id="ptd-benefit" name="additional-checkbox" value="PTD Benefit" onchange="toggleCheck('ptd-benefit', 'ptd', 'ptd_status')">
  <input type="text" min="1" hidden value="{{ $new_record->ptd_status ?? ''}}" class="form-control" id="ptd_status" name="ptd_status">
  <label for="ptd-benefit">PTD Benefit</label>
</div>

<!-- TTD Benefit -->
<div class="mb-2">
  <input type="checkbox" id="ttd-benefit" name="additional-checkbox" value="TTD Benefit" onchange="toggleCheck('ttd-benefit', 'ttd', 'ttd_status')">
  <input type="text" min="1" hidden value="{{ $new_record->ttd_status ?? ''}}" class="form-control" id="ttd_status" name="ttd_status">
  <label for="ttd-benefit">TTD Benefit</label>
</div>

<!-- Group Spouse Benefit -->
<div class="mb-2">
  <input type="checkbox" id="group-spouse-benefit" name="additional-checkbox" value="Group Spouse Benefit" onchange="toggleCheck('group-spouse-benefit', 'complementary_benefits', 'cb_status')">
  <input type="text" min="1" hidden value="{{ $new_record->cb_status ?? ''}}" class="form-control" id="cb_status" name="cb_status">
  <label for="group-spouse-benefit">Group Spouse Benefit</label>
</div>
</div>

<div class="col-md-6">
  <!-- GPA Benefit -->
  <div class="mb-2">
    <input type="checkbox" id="gpa-benefit" name="additional-checkbox" value="GPA Benefit" onchange="toggleCheck('gpa-benefit', 'group_personal_accident_benefit', 'gpa_status')">
    <input type="text" min="1" hidden value="{{ $new_record->gpa_status ?? ''}}" class="form-control" id="gpa_status" name="gpa_status">
    <label for="gpa-benefit">GPA Benefit</label>
  </div>

  <!-- WC Benefit -->
  <div class="mb-2">
    <input type="checkbox" id="wc-benefit" name="additional-checkbox" value="WC Benefit" onchange="toggleCheck('wc-benefit', 'workmen_compensation_benefits', 'wc_status')">
    <input type="text" min="1" hidden value="{{ $new_record->wc_status ?? ''}}" class="form-control" id="wc_status" name="wc_status">
    <label for="wc-benefit">WC Benefit</label>
  </div>

  <!-- Funeral Benefit -->
  <div class="mb-2">
    <input type="checkbox" id="funeral-benefit" name="additional-checkbox" value="Funeral Benefit" onchange="toggleCheck()">
    <label for="funeral-benefit">Funeral Benefit</label>
  </div>

  <!-- Hospitalization -->
  <div class="mb-2">
    <input type="checkbox" id="hospitalization" name="additional-checkbox" value="Hospitalization" onchange="toggleCheck('hospitalization', 'hospital_cash', 'hc_status')">
    <input type="text" min="1" hidden value="{{ $new_record->hc_status ?? ''}}" class="form-control" id="hc_status" name="hc_status">
    <label for="hospitalization">Hospitalization</label>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> --}}




{{-- <div id="ci">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingFifteen" data-toggle="collapse" role="button" data-target="#collapseFifteenAccident" aria-expanded="false" aria-controls="collapseFifteenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Critical Illness (CI) </code>
                    </span>
                </div>

                <div id="collapseFifteenAccident" class="collapse" aria-labelledby="headingFifteen" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                              

                                <div class="col-auto">
                                    <label for="ci-as-a-percentage-of-gla-benefit">CI as a percentage of GLA
                                        Benefit</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" value="{{ $new_record->ci_as_a_percentage_of_gla_benefit ?? ''}}" class="form-control" id="ci_as_a_percentage_of_gla_benefit" name="ci_as_a_percentage_of_gla_benefit">
</div>
</div>

<div class="col-auto">
  <label for="maximum-ci-benefit">Maximum CI benefit</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" value="{{ $new_record->ci_maximum_ci_benefit ?? ''}}" id="ci_maximum_ci_benefit" name="ci_maximum_ci_benefit">
  </div>
</div>

<div class="col-auto">
  <label for="cover-termination-age">Cover Termination Age</label>
  <div class="form-label-group">
    <input type="number" min="1" value="{{ $new_record->ci_cover_termination_age ?? ''}}" class="form-control" id="ci_cover_termination_age" name="ci_cover_termination_age">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> --}}





{{-- <div id="ptd">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingSixteen" data-toggle="collapse" role="button" data-target="#collapseSixteenAccident" aria-expanded="false" aria-controls="collapseSixteenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Permanent and Total Disability (PTD) </code>
                    </span>
                </div>

                <div id="collapseSixteenAccident" class="collapse" aria-labelledby="headingSixteen" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">
                                <div class="col-auto">
                                    <label for="cover-type">Cover Type</label>
                                    <div class="form-label-group">
                                        <select id="ptd_cover_type" name="ptd_cover_type" value="{{ $new_record->ptd_cover_type ?? ''}}" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
<option value="">-- Select cover type --</option>
</select>
<label for="cover-type"> Cover Type </label>
</div>
</div>

<div class="col-auto">
  <label for="multiple-of-salary">Multiple of Salary</label>
  <div class="form-label-group">
    <select id="ptd_multiple_of_salary" name="ptd_multiple_of_salary" value="{{ $new_record->ptd_multiple_of_salary ?? ''}}" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
      <option value="1">-- Select exchange rate --</option>
    </select>
    <label for="multiple-of-salary"> Multiple of Salary</label>
  </div>
</div>

<div class="col-auto">
  <label for="product-type">Product Type</label>
  <div class="form-label-group">
    <select id="ptd_product_type" name="ptd_product_type" rows="3" tabindex="1" value="{{ $new_record->ptd_product_type ?? ''}}" data-placeholder="Select here.." style="width:100%">
      <option value="">-- Select product type --</option>
      @foreach($producttypes as $product)
      <option value="{{ $product->type }}">{{ $product->type }}</option>
      @endforeach
    </select>
    <label for="product-type"> Product Type </label>
  </div>
</div>

<div class="col-auto">
  <label for="cover-termination-age">Cover Termination Age</label>
  <div class="form-label-group">
    <input type="number" min="1" value="{{ $new_record->ptd_cover_termination_age ?? ''}}" class="form-control" id="ptd_cover_termination_age" name="ptd_cover_termination_age">
  </div>
</div>

<div class="col-auto">
  <label for="deferred-period">Deferred Period</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" value="{{ $new_record->ptd_deferred_period ?? ''}}" id="ptd_deferred_period" name="ptd_deferred_period">
  </div>
</div>

<div class="col-auto">
  <label for="disability-definition">Disability Definition</label>
  <div class="form-label-group">
    <input type="text" min="1" class="form-control" id="ptd_disability_definition" value="{{ $new_record->ptd_disability_definition ?? ''}}" name="ptd_isability_definition">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--</div>-->
</div>

</div> --}}


{{-- <div id="ttd">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingSeventeen" data-toggle="collapse" role="button" data-target="#collapseSeventeenAccident" aria-expanded="false" aria-controls="collapseSeventeenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Total and Temporal Disability (TTD) </code>
                    </span>
                </div>

                <div id="collapseSeventeenAccident" class="collapse" aria-labelledby="headingSeventeen" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-label-group">
                                        <select id="ttd_cover_type" name="ttd_cover_type" rows="3" value="{{ $new_record->ttd_cover_type ?? ''}}" tabindex="1" data-placeholder="Select here.." style="width:100%">
<option value="">-- Select cover type --</option>
</select>
<label for="cover-type"> Cover Type </label>
</div>
</div>

<div class="col-auto">
  <label for="cover-termination-age">Cover Termination Age</label>
  <div class="form-label-group">
    <input type="number" min="1" value="{{ $new_record->ttd_cover_termination_age ?? ''}}" class="form-control" id="ttd_cover_termination_age" name="ttd_cover_termination_age">
  </div>
</div>
<div class="col-auto">
  <label for="deferred-period">Deferred Period</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="ttd_deferred_period" value="{{ $new_record->ttd_deferred_period ?? ''}}" name="ttd_deferred_period">
  </div>
</div>
<div class="col-auto">
  <label for="disability-definition">Disability Definition</label>
  <div class="form-label-group">
    <input type="text" min="1" class="form-control" id="ttd_disability_definition" value="{{ $new_record->ttd_disability_definition ?? ''}}" name="ttd_disability_definition">
  </div>
</div>

<div class="col-auto">
  <label for="weekly-benefits">Weekly Benefits</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="ttd_weekly_benefits" value="{{ $new_record->ttd_weekly_benefits ?? ''}}" name="ttd_weekly_benefits">
  </div>
</div>

<div class="col-auto">
  <label for="maximum-number-of-payments">Maximum Number of Payments</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="ttd_maximum_number_of_payments" value="{{ $new_record->ttd_maximum_number_of_payments ?? ''}}" name="ttd_maximum_number_of_payments">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--</div>-->
</div>

</div> --}}

{{-- <div id="spouse_group_life_assurance">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingEighteen" data-toggle="collapse" role="button" data-target="#collapseEighteenAccident" aria-expanded="false" aria-controls="collapseEighteenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Spouse's Group Life Assurance (SGLA) </code>
                    </span>
                </div>

                <div id="collapseEighteenAccident" class="collapse" aria-labelledby="headingEighteen" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">


                                <div class="col-auto">
                                    <label for="cover-termination-age">Cover Termination Age</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" value="{{ $new_record->sgla_cover_termination_age ?? ''}}" class="form-control" id="sgla_cover_termination_age" name="sgla_cover_termination_age">
</div>
</div>

<div class="col-auto">
  <label for="maximum-number-of-payments">Maximum Number of Payments</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" value="{{ $new_record->sgla_maximum_number_of_payments ?? ''}}" id="sgla_maximum_number_of_payments" name="sgla_maximum_number_of_payments">
  </div>
</div>

<div class="col-auto">
  <label for="maximum-benefits">Maximum Benefits</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="sgla_maximum_benefits" value="{{ $new_record->sgla_maximum_benefits ?? ''}}" name="sgla_maximum_benefits">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--</div>-->
</div>

</div> --}}



{{-- <div id="complementary_benefits">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingNineteen" data-toggle="collapse" role="button" data-target="#collapseNineteenAccident" aria-expanded="false" aria-controls="collapseNineteenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code>Complementary Benefits </code>
                    </span>
                </div>

                <div id="collapseNineteenAccident" class="collapse" aria-labelledby="headingNineteen" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                                <div class="col-auto">
                                    <label for="spouse-benefit">Spouse benefit</label>
                                    <div class="form-label-group">
                                        <select id="cb_spouse_benefit" value="{{ $new_record->cb_spouse_benefit ?? ''}}" name="cb_spouse_benefit" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
<option value="">-- Select product type --</option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
<label for="spouse-benefit">Spouse benefits </label>
</div>
</div>

<div class="col-auto">
  <label for="spouse">Spouse</label>
  <div class="form-label-group">
    <input type="number" min="1" value="{{ $new_record->cb_spouse ?? ''}}" class="form-control" id="cb_spouse" name="cb_spouse">
  </div>
</div>

<div class="col-auto">
  <label for="number-of-spouses">Number of Spouses</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" value="{{ $new_record->cb_number_of_spouses ?? ''}}" id="cb_number_of_spouses" name="cb_number_of_spouses">
  </div>
</div>

<div class="col-auto">
  <label for="children">children</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" value="{{ $new_record->cb_children ?? ''}}" id="cb_children" name="cb_children">
  </div>
</div>

<div class="col-auto">
  <label for="number-of-children">Number of Children</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="cb_number_of_children" value="{{ $new_record->cb_number_of_children ?? ''}}" name="cb_number_of_children">
  </div>
</div>
</div>
<hr>
<div class="row">

  <div class="col-auto">
    <label for="funeral-expense-benefit">Funeral Expense benefit</label>
    <div class="form-label-group">
      <select id="funeral_expense_benefit" name="funeral_expense_benefit" rows="3" value="{{ $new_record->funeral_expense_benefit ?? ''}}" tabindex="1" data-placeholder="Select here.." style="width:100%">
        <option value="yes">YES</option>
        <option value="no">NO</option>
      </select>
      <label for="funeral-expense-benefit">Funeral Expense benefit </label>
    </div>
  </div>

  <div class="col-auto">
    <label for="amount-per-member">Amount per Member</label>
    <div class="form-label-group">
      <input type="number" min="1" class="form-control" value="{{ $new_record->cb_amount_per_member ?? ''}}" id="cb_amount_per_member" name="cb_amount_per_member">
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
<!--</div>-->
</div>

</div> --}}

{{-- <div id="group_family_funeral">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingNineteen" data-toggle="collapse" role="button" data-target="#collapseNineteenAccident" aria-expanded="false" aria-controls="collapseNineteenAccident">
                    <span class="lead collapse-title font-small-3">
                        <code>Group Family Funeral </code>
                    </span>
                </div>

                <div id="collapseNineteenAccident" class="collapse" aria-labelledby="headingNineteen" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                                <div class="col-auto">
                                    <label for="member">Member</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" class="form-control" value="{{ $new_record->gff_member ?? ''}}" id="gff_member" name="gff_member">
</div>
</div>
<div class="col-auto">
  <label for="spouse">Spouse</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gff_spouse" value="{{ $new_record->gff_spouse ?? ''}}" name="gff_spouse">
  </div>
</div>

<div class="col-auto">
  <label for="children">children</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gff_children" value="{{ $new_record->gff_children ?? ''}} name=" gff_children">
  </div>
</div>

<div class="col-auto">
  <label for="parent">Parent</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gff_parent" value="{{ $new_record->gff_parent ?? ''}} name=" gff_parent">
  </div>
</div>

<div class="col-auto">
  <label for="parent-in-law">Parent-in-law</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gff_parent_in_law" value="{{ $new_record->gff_parent_in_law ?? ''}} name=" gff_parent_in_law">
  </div>
</div>

<div class="col-auto">
  <label for="number-of-spouses">Number of Spouses</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gff_number_of_spouses" value="{{ $new_record->gff_number_of_spouses ?? ''}} name=" gff_number_of_spouses">
  </div>
</div>



<div class="col-auto">
  <label for="number-of-children">Max number of Children covered</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gff_number_of_children" value="{{ $new_record->gff_number_of_children ?? ''}} name=" gff_number_of_children">
  </div>
</div>
</div>
<div class="row">
  <div class="col-auto">
    <label for="number-of-parents">Number of Parents</label>
    <div class="form-label-group">
      <input type="number" min="1" class="form-control" id="gff_number_of_parents" value="{{ $new_record->gff_number_of_parents ?? ''}} name=" gff_number_of_parents">
    </div>
  </div>
</div>
<div class="col-auto">
  <label for="number-of-parent-in-law">Number of Parent-in-law</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gff_number_of_parent_in_law" value="{{ $new_record->gff_number_of_parent_in_law ?? ''}} name=" gff_number_of_parent_in_law">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>

</div> --}}



{{-- <div id="group_personal_accident_benefit">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingTwenty" data-toggle="collapse" role="button" data-target="#collapseTwentyAccident" aria-expanded="false" aria-controls="collapseTwentyAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Group personal accident benefit </code>
                    </span>
                </div>

                <div id="collapseTwentyAccident" class="collapse" aria-labelledby="headingTwenty" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                                <div class="col-auto">
                                    <label for="multiple-of-salary">Multiple of Salary</label>
                                    <div class="form-label-group">
                                        <select id="gpa_multiple_of_salary" name="gpa_multiple_of_salary" value="{{ $new_record->gpa_multiple_of_salary ?? ''}}" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
<option value="">-- Select exchange rate --</option>
</select>
<label for="multiple-of-salary"> Multiple of Salary</label>
</div>
</div>

<div class="col-auto">
  <label for="accidental-permnent">Accidental Permnent (TDP) Rate SA (GHS)
  </label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gpa_accidental_permnent" value="{{ $new_record->gpa_accidental_permnent ?? ''}}" name="gpa_accidental_permnent">
  </div>
</div>

<div class="col-auto wellin">
  <label for="accidental-temporal-disability">Accidental Temporal Disability (TTD) Rate SA (GHS)
  </label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gpa_accidental_temporal_disability" value="{{ $new_record->gpa_accidental_temporal_disability ?? ''}}" name="gpa_accidental_temporal_disability">
  </div>
</div>

<div class="col-auto welloff">
  <label for="annual-medical-sum-assured-per-member">Annual Medical Sum Assured
    per
    Member (% of TPD cover)</label>
  <div class="form-label-group welloff">
    <input type="number" min="1" class="form-control" value="{{ $new_record->gpa_annual_medical_sum_assured_per_member ?? ''}}" id="gpa_annual_medical_sum_assured_per_member" name="gpa_annual_medical_sum_assured_per_member">
  </div>
</div>

<div class="col-auto welloff ">
  <label for="medical-expense-sum-assured">Medical Expense Sum Assured </label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gpa_medical_expense_sum_assured" value="{{ $new_record->gpa_medical_expense_sum_assured ?? ''}}" name="gpa_medical_expense_sum_assured">
  </div>
</div>

<div class="col-auto">
  <label for="discount-rate">Discount Rate</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="gpa_discount_rate" value="{{ $new_record->gpa_discount_rate ?? ''}}" name="gpa_discount_rate">
  </div>
</div>
</div>
</div>

</div>
</div>
</div>
<!--</div>-->
</div>

</div> --}}

{{-- <div id="medical_expense">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingForty" data-toggle="collapse" role="button" data-target="#collapseFortyAccident" aria-expanded="false" aria-controls="collapseFortyAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Medical Expense (ME)</code>
                    </span>
                </div>

                <div id="collapseFortyAccident" class="collapse" aria-labelledby="headingForty" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                                <div class="col-auto">
                                    <label for="annual-medical-sum-assured-per-member">Annual Medical Sum Assured
                                        per
                                        Member (% of TPD cover)</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" class="form-control" value="{{ $new_record->me_annual_medical_sum_assured_per_member ?? ''}}" id="me_annual_medical_sum_assured_per_member" name="me_annual_medical_sum_assured_per_member">
</div>
</div>

<div class="col-auto">
  <label for="medical-expense-sum-assured">Medical Expense Sum Assured </label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="me_medical_expense_sum_assured" value="{{ $new_record->me_medical_expense_sum_assured ?? ''}}" name="me_medical_expense_sum_assured">
  </div>
</div>

<div class="col-auto">
  <label for="discount-rate">Discount Rate</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="me_discount_rate" value="{{ $new_record->me_discount_rate ?? ''}}" name="me_discount_rate">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--</div>-->
</div>

</div> --}}

{{-- <div id="workmen_compensation_benefits">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingUno" data-toggle="collapse" role="button" data-target="#collapseUnoAccident" aria-expanded="false" aria-controls="collapseUnoAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Workmen's Compensation Benefits (WC) </code>
                    </span>
                </div>

                <div id="collapseUnoAccident" class="collapse" aria-labelledby="headingUno" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">

                                <div class="col-auto">
                                    <label for="workmens-compensation-sa">Workmens Compensation SA (GHS)</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" class="form-control" id="wc_workmens_compensation_sa" value="{{ $new_record->wc_workmens_compensation_sa ?? ''}}" name="wc_workmens_compensation_sa">
</div>
</div>
<div class="col-auto">
  <label for="wc-endorsement">WC endorsement</label>
  <div class="form-label-group">
    <select id="wc_endorsement" name="wc_endorsement" rows="3" tabindex="1" data-placeholder="Select here.." value="{{ $new_record->wc_endorsement ?? ''}}" style="width:100%">
      <option value="yes">YES</option>
      <option value="no">NO</option>
    </select>
    <label for="wc_endorsement">Funeral Expense benefit </label>
  </div>
</div>
<div class="col-auto">
  <label for="discount-rate">Discount Rate</label>
  <div class="form-label-group">
    <input type="number" min="1" class="form-control" id="wc_discount_rate" value="{{ $new_record->wc_discount_rate ?? ''}}" name="wc_discount_rate">
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--</div>-->
</div>

</div> --}}


{{-- <div id="hospital_cash">
        <div class="collapse-margin">
            <div class="panel panel-warning">
                <div class="card-header collapsed" id="headingDeux" data-toggle="collapse" role="button" data-target="#collapseDeuxAccident" aria-expanded="false" aria-controls="collapseDeuxAccident">
                    <span class="lead collapse-title font-small-3">
                        <code> Hospital Cash (HC)</code>
                    </span>
                </div>

                <div id="collapseDeuxAccident" class="collapse" aria-labelledby="headingDeux" data-parent="#accordionAccident">
                    <!--<div id="collapseOneMobileMoney" class="panel-collapse collapse">-->
                    <div class="panel-body text-sm">
                        <div class="container">
                            <div class="row">
                                <div class="col-auto">
                                    <label for="amount-payable-per-night">Amount payable per night (GHS)</label>
                                    <div class="form-label-group">
                                        <input type="number" min="1" class="form-control" value="{{ $new_record->hc_amount_payable_per_night ?? ''}}" id="hc_amount_payable_per_night" name="hc_amount-payable-per-night">
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> --}}
{{-- </div> --}}


</style>



<div id="marineinsurance" name="marineinsurance">
  <section id="accordion-with-margin">
    <div class="row">
      <div class="col-sm-12">
        <div class="card collapse-icon accordion-icon-rotate">

          <div class="card-body">

            <div class="accordion" id="MarineAccordion">

              <div class="collapse-margin">
                <div class="card-header" id="headingTwo" data-toggle="collapse" role="button" data-target="#MarineTwo" aria-expanded="false" aria-controls="MarineTwo">

                </div>
                <div id="MarineTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#MarineAccordion">
                  <div class="card-body">
                    <!-- // Basic multiple Column Form section start -->
                    <section id="multiple-column-form">
                      <div class="row match-height">
                        <div class="col-12">

                          <div class="alert alert-primary mb-2" role="alert">

                          </div>
                          <div class="card">

                            <div class="card-content">
                              <div class="card-body">

                                <div class="form-body">
                                  <div class="row">
                                    <div class="col-md-2 col-12">
                                      <label for="first-name-column">Risk Type</label>
                                      <div class="form-label-group">
                                        <select id="marine_risk_type" name="marine_risk_type" required rows="3" tabindex="1" onchange="getCommissionRates(this)" data-placeholder="Select coverage .." class="form-control m-b" style="width: 100%;">
                                          <option value="">-- Select coverage --</option>
                                          @foreach($marinetypes as $marinetypes)
                                          <option value="{{ $marinetypes->type }}">{{ $marinetypes->type }}</option>
                                          @endforeach
                                        </select>

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="first-name-column">Means of Conveyance</label>
                                      <div class="form-label-group">
                                        <select id="marine_means_of_conveyance" name="marine_means_of_conveyance" required rows="3" tabindex="1" data-placeholder="Select shipping type ..." style="width:100%">
                                          <option value="">-- Select Shiping Type --</option>
                                          <option value="Land">Land</option>
                                          <option value="Sea">Sea</option>
                                          <option value="Air">Air</option>
                                        </select>

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Vessel / Flight Name</label>
                                      <div class="form-label-group">
                                        <input type="text" placeholder="" required class="form-control block-mask text-uppercase" id="marine_vessel" value="{{ Request::old('marine_vessel') ?: '' }}" name="marine_vessel" style="width:100%">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Vessel / Flight Number</label>
                                      <div class="form-label-group">
                                        <input type="text" placeholder="" required class="form-control block-mask text-uppercase" id="marine_vessel_number" value="{{ Request::old('marine_vessel_number') ?: '' }}" name="marine_vessel_number" style="width:100%">

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="company-column">Vessel Flag</label>
                                      <div class="form-label-group">
                                        <input type="text" placeholder="" class="form-control" id="marine_vessel_flag block-mask text-uppercase" value="{{ Request::old('marine_vessel_flag') ?: '' }}" name="marine_vessel_flag" style="width:100%">

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Carrier</label>
                                      <div class="form-label-group">
                                        <select id="marine_carrier" name="marine_carrier" aria-placeholder="Carrier" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                          <option value="">-- Select carrier --</option>
                                          @foreach($ports as $port)
                                          <option value="{{ $port->carrier }}">{{ $port->carrier }}</option>
                                          @endforeach
                                        </select>

                                      </div>
                                    </div>


                                  </div>
                                  <hr>



                                  <div class="row">
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Sum Insured</label>
                                      <div class="form-label-group">
                                        <input type="text" min="0" value="0" step="0.01" required class="form-control" id="marine_sum_insured" value="{{ Request::old('marine_sum_insured') ?: '' }}" name="marine_sum_insured">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Marine Rate (%)</label>
                                      <div class="form-label-group">
                                        <input type="number" min="0" value="0" step="0.01" required class="form-control" id="marine_rate" value="{{ Request::old('marine_rate') ?: '' }}" name="marine_rate">

                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Sailing/Flight Date</label>
                                      <div class="form-label-group">
                                        <input type="text" placeholder="Sailing/Flight Date" required class="form-control" id="voyage_date" value="{{ Request::old('voyage_date') ?: '' }}" name="voyage_date">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Estimated Departure Date</label>
                                      <div class="form-label-group">
                                        <input type="text" class="form-control" id="departure_date" required value="{{ Request::old('departure_date') ?: '' }}" name="departure_date">

                                      </div>
                                    </div>


                                    <div class="col-md-2 col-12">
                                      <label for="company-column">Est. Date of Arrival</label>
                                      <div class="form-label-group">
                                        <input type="text" class="form-control" id="arrival_date" value="{{ Request::old('arrival_date') ?: '' }}" name="arrival_date">

                                      </div>
                                    </div>



                                  </div>

                                  <hr>




                                  <div class="row">
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Country of Origin</label>
                                      <div class="form-label-group">
                                        <select class="custom-select font-small-3 form-control required" id="marine_country_of_importation" required name="marine_country_of_importation" onchange="loadPortImportation()" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                          <option value="">-- Select country --</option>
                                          @foreach($ports as $port)
                                          <option value="{{ $port->country }}">{{ $port->country }}</option>
                                          @endforeach
                                        </select>
                                        <label for="country-floating">Country of Importation/Exportation</label>
                                      </div>
                                    </div>


                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Country of Destination</label>
                                      <div class="form-label-group">
                                        <select id="marine_country_of_destination" required name="marine_country_of_destination" onchange="loadPortDestination()" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                          <option value="">-- Select country --</option>
                                          @foreach($ports as $port)
                                          <option value="{{ $port->country }}">{{ $port->country }}</option>
                                          @endforeach
                                        </select>
                                        <label for="country-floating">Country of Destination</label>
                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Port of Loading</label>
                                      <div class="form-label-group">
                                        <select id="marine_port_of_loading" required name="marine_port_of_loading" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                          <option value="">-- Select Port of Loading --</option>

                                        </select>
                                        <label for="country-floating">Port of Loading</label>
                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating"> Port of Destination</label>
                                      <div class="form-label-group">
                                        <select id="marine_port_of_destination" required name="marine_port_of_destination" rows="3" tabindex="1" data-placeholder="Select here.." style="width:100%">
                                          <option value="">-- Select Port of Destination --</option>

                                        </select>
                                        <label for="country-floating"> Port of Destination</label>
                                      </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Commercial Invoice Number</label>
                                      <div class="form-label-group">
                                        <input type="text" class="form-control required" id="marine_valuation" placeholder="" value="{{ Request::old('marine_valuation') ?: '' }}" name="marine_invoice_number">

                                      </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Bill of Lading Number</label>
                                      <div class="form-label-group">
                                        <input type="text" class="form-control" id="marine_bill_of_landing" placeholder="" value="{{ Request::old('marine_bill_of_landing') ?: '' }}" name="marine_bill_of_landing">

                                      </div>
                                    </div>



                                  </div>
                                  <hr>



                                  <div class="row">
                                    <div class="col-md-12 col-12">
                                      <div class="form-label-group">
                                        <textarea type="text" required class="form-control block-mask text-uppercase" placeholder="Interest & Marks" rows="3" id="marine_interest" value="{{ Request::old('marine_interest') ?: '' }}" name="marine_interest"></textarea>
                                        <label for="email-id-column"> Interest & Marks </label>
                                      </div>
                                    </div>
                                  </div>
                                  <br>


                                  <div class="row">
                                    <div class="col-md-12 col-12">
                                      <div class="">
                                        <label for="email-id-column"> Description of insured goods </label>
                                        <textarea type="text" required class="form-control block-mask text-uppercase" placeholder="Description of Insured Goods" rows="3" id="marine_condition" value="{{ Request::old('marine_condition') ?: '' }}" name="marine_condition"></textarea>

                                      </div>
                                    </div>
                                  </div>
                                  <br>










                                  <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">

                                    <input type="hidden" id="marinekey" name="marinekey" value="{{ Request::old('marinekey') ?: '' }}">
                                    <button type="button" name="btnmarine" id="btnmarine" onclick="addMarineDetails()" class="btn btn-sm btn-primary rounded pull-left waves-effect waves-light">+ Add Marine Details</button>

                                  </div>

                                </div>

                                <!-- <img src="/images/cargo-insurance-banner.png"> -->

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
                    <!-- // Basic Floating Label Form section end -->

                  </div>
                </div>
              </div>

              <div class="collapse-margin">
                <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#MarineOne" aria-expanded="false" aria-controls="MarineOne">

                </div>

                <div id="MarineOne" class="collapse show" aria-labelledby="headingOne" data-parent="#MarineAccordion">
                  <div class="card-body">
                    <div class="panel-body">
                      <div class="table-responsive">
                        <table id="marineScheduleTable" class="table table-striped table-hover mb-0 font-small-2 text-center">
                          <thead>
                            <tr>

                              <th>Vessel Number</th>
                              <th>Voyage</th>
                              <th>Means of Conveyance</th>
                              <th>Sum Insured</th>
                              <th>Rate</th>
                              <th>Premium</th>
                              <th>Created On</th>
                              <th>Created By</th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>

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
  </section>
  <!-- Accordion with margin end -->
</div>




<!-- travel insurance starts from here -->
<div id="travelinsurance" name="travelinsurance">
  <section id="accordion-with-margin">
    <div class="row">
      <div class="col-sm-12">
        <div class="card collapse-icon accordion-icon-rotate">

          <div class="card-body">

            <div class="accordion" id="accordionTravel">

              <div class="collapse-margin">
                <div class="card-header" id="accordionTravelheadingTwo" data-toggle="collapse" role="button" data-target="#accordionTravelcollapseTwo" aria-expanded="false" aria-controls="accordionTravelcollapseTwo">

                </div>
                <div id="accordionTravelcollapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionTravel">
                  <div class="card-body">
                    <!-- // Basic multiple Column Form section start -->
                    <section id="multiple-column-form">
                      <div class="row match-height">
                        <div class="col-12">

                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="travel_product_name">
                                  Product
                                </label>
                                <select id="travel_product_name" name="travel_product_name" class="custom-select form-control required" onchange="getProductPlan();computeTravelPremium();" style="width:100%">
                                  <option value=""></option>
                                  @foreach($travel_products as $products)
                                  <option value="{{ $products->product_name }}">{{ $products->product_name }}</option>
                                  @endforeach

                                </select>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="travel_product_plan">
                                  Plan
                                </label>
                                <select id="travel_product_plan" name="travel_product_plan" class="custom-select form-control required" onchange="getCountryDestList();computeTravelPremium();" style="width:100%">
                                  <option value=""></option>
                                </select>
                              </div>
                            </div>


                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="travel_country_of_dest">
                                  Country of destination
                                </label>
                                <select id="travel_country_of_dest" name="travel_country_of_dest" class="custom-select form-control required" onchange="computeTravelPremium();" style="width:100%">
                                  <option value=""></option>
                                </select>
                              </div>
                            </div>


                          </div>

                          <div class="alert alert-primary mb-2" role="alert">

                          </div>

                          <div class="card">

                            <div class="card-content" style="font-size: 17px;">
                              <div class="card-body">

                                <div class="form-body">


                                  <div class="row">
                                    <div class="col-md-2 col-12">
                                      <label for="country-floating">Passport Number <sup class="text-danger font-medium-1"> * </sup> </label>
                                      <div class="form-label-group">
                                        <input type="text" @if ($customers->id_type == 'Passport')
                                        value="{{ $customers->id_number }}"
                                        @else
                                        value = ""
                                        @endif
                                        class="form-control" id="travel_passport_number" name="travel_passport_number">

                                      </div>
                                    </div>
                                  </div>

                                  <br>
                                  <strong>Medical Conditions</strong>

                                  <br>
                                  <br>
                                  <br>
                                  <div class=" travel-questions-div">
                                    <div class="row1 justify-content-space-between">
                                      <div class="ques-text">
                                        Do you have any pre-existing medical condition(s) prior to the commencement of this trip?
                                      </div>
                                      <div class="radio-btns col-1">
                                        <div class="row">
                                          <div class="radio-btn-div form-check">
                                            <input class="form-check-input" type="radio" name="existing_medical_condition" id="existing_medical_condition">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                              Yes
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="existing_medical_condition" id="existing_medical_condition" checked>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                              No
                                            </label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <br>



                                  <div class=" travel-questions-div">
                                    <div class="row1 justify-content-space-between">
                                      <div class="ques-text">
                                        Have you received medical advice or treatment (including medication) for hypertension 2 years prior to this trip?
                                      </div>
                                      <div class="radio-btns col-1">
                                        <div class="row">
                                          <div class="radio-btn-div form-check">
                                            <input class="form-check-input" type="radio" name="medical_advice" id="medical_advice">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                              Yes
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="medical_advice" id="medical_advice" checked>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                              No
                                            </label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <br>

                                  <div class=" travel-questions-div">
                                    <div class="row1 justify-content-space-between">
                                      <div class="ques-text">
                                        Have you been under treatment or medical supervision during the 12 months prior to the start of this trip?
                                      </div>
                                      <div class="radio-btns col-1">
                                        <div class="row">
                                          <div class="radio-btn-div form-check">
                                            <input class="form-check-input" type="radio" name="medical_supervision" id="medical_supervision">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                              Yes
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="medical_supervision" id="medical_supervision" checked>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                              No
                                            </label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <br>


                                  <section id="accordion-with-margin">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="card collapse-icon accordion-icon-rotate">

                                          <div class="card-body">

                                            <div class="accordion" id="accordionAccident" data-toggle-hover="true">
                                              <div class="collapse-margin">
                                                <div class="card-header collapsed" id="headingOne" data-toggle="collapse" role="button" data-target="#collapseOneAccident" aria-expanded="false" aria-controls="collapseOneEngineering">
                                                  <span class="lead collapse-title font-small-3">
                                                    <code> Family Details </code>
                                                  </span>
                                                </div>

                                                <div id="collapseOneAccident" class="collapse" aria-labelledby="headingOne" data-parent="#accordionAccident" style="">
                                                  <div class="card-body">
                                                    <p>This portion should be completed if the applicant is traveling with his/her spouse and/or children (Maximum of three)</p>
                                                    <div class="row">
                                                      <div class="col-md-2 col-12">
                                                        <label for="country-floating" style="">First Name<sup class="text-danger font-medium-1"> * </sup> </label>
                                                        <div class="form-label-group">
                                                          <input type="text" class="form-control" id="travel_family_first_name" value="{{ Request::old('travel_family_first_name') ?: '' }}" name="travel_family_first_name">

                                                        </div>
                                                      </div>

                                                      <div class="col-md-2 col-12">
                                                        <label for="country-floating" style="">Last Name<sup class="text-danger font-medium-1"> * </sup> </label>
                                                        <div class="form-label-group">
                                                          <input type="text" class="form-control" id="travel_family_last_name" value="{{ Request::old('travel_family_last_name') ?: '' }}" name="travel_family_last_name">

                                                        </div>
                                                      </div>

                                                      <div class="col-md-2">
                                                        <div class="form-group">
                                                          <label for="gender">
                                                            Gender <sup class="text-danger font-medium-1"> * </sup>
                                                          </label>
                                                          <select class="custom-select form-control required" id="travel_family_gender" name="travel_family_gender">
                                                            <option value=""></option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                          </select>
                                                        </div>
                                                      </div>

                                                      <div class="col-md-2">
                                                        <div class="form-group">
                                                          <label for="travel_family_date_of_birth">
                                                            Date Of Birth
                                                          </label>
                                                          <input type="text" class="form-control required" id="travel_family_date_of_birth" name="travel_family_date_of_birth">
                                                        </div>
                                                      </div>






                                                      <div class="col-md-2 col-12">
                                                        <label for="country-floating">Passport Number <sup class="text-danger font-medium-1"> * </sup> </label>
                                                        <div class="form-label-group">
                                                          <input type="text" class="form-control" id="travel_family_passport_number" value="{{ Request::old('travel_family_passport_number') ?: '' }}" name="travel_family_passport_number">
                                                        </div>
                                                      </div>

                                                    </div>

                                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                      <input type="hidden" name="travelkey" id="travelkey" value="{{ Request::old('travelkey') ?: '' }}">
                                                      <button class="btn btn-sm btn-primary rounded pull-left waves-effect waves-light" id="btntravel" name="btntravel" onclick="addTravelDetails()" type="button">+ Click to Add </button>
                                                    </div>
                                                  </div>
                                                </div>

                                              </div>
                                            </div>


                                          </div>
                                        </div>


                                  </section>


                                  <div class="col-sm-6">
                                    <a class="badge badge-pill badge-glow badge-success mr-1 mb-1 pull-right text-default" style="color: white;padding:2%;" onclick="computeTravelPremium();">Compute Premium </a>
                                  </div>






                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
                    <!-- // Basic Floating Label Form section end -->

                  </div>
                </div>
              </div>

              <div class="collapse-margin">
                <div class="card-header" id="accordionAccidentheadingOne" data-toggle="collapse" role="button" data-target="#accordionAccidentcollapseOne" aria-expanded="false" aria-controls="accordionAccidentcollapseOne">

                </div>

                <div id="accordionTravelcollapseOne" class="collapse show" aria-labelledby="accordionAccidentheadingOne" data-parent="#accordionTravel">
                  <div class="card-body">
                    <div class="panel-body">
                      <div class="table-responsive">

                        <div class="form-check" style="font-size: 10px;">
                          <input class="form-check-input" type="checkbox" id="policy_minor_holder_status" onclick="getTotalTravelPremium();">
                          <label class="form-check-label" for="flexCheckDefault">
                            Check if minor is the one travelling
                          </label>
                        </div>
                        <br>

                        <table id="travelScheduleTable" class="table table-striped table-hover mb-0 font-small-2 text-center">
                          <thead>
                            <tr>

                              <th>Name</th>
                              <th>Gender</th>
                              <th>Date of Birth</th>
                              <th>Passport #</th>
                              <th>Premium</th>
                              <th>Created on</th>
                              <th>Created by</th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>

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
  </section>
  <!-- Accordion with margin end -->
</div>

<!-- End travel insurance form -->









<!-- <div class="alert alert-primary mb-2" role="alert">
                                
                  </div>


                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                          <label for="location">
                            <code> Loss Payee </code>
                          </label>
                          <select class="custom-select form-control" multiple id="policy_interest" name="policy_interest[]" data-placeholder="" style="width: 100%;">
                            @foreach($banks as $bank)
                            <option value="{{ $bank->name }}"> {{ $bank->name }}</option>
                            @endforeach
                           
                          </select>
                      </div>
                  </div>
                  </div>

                

                  <div id="clauseform" name="clauseform">
                    <div class="row">

                      <div class="col-md-12 font-small-3">
                        <div class="form-group">
                            <label for="policy_clause">
                               <code> Clauses/Extention/Warranties/Exclusions </code>
                            </label>
                          
                            <select class="custom-select font-small-3 form-control required"  multiple id="policy_clause" name="policy_clause[]" data-placeholder="Select clauses ..." style="width: 100%;">
                               
                             
                            </select>
                        </div>
                    </div>
                    </div>
                  </div> -->

<div class="alert alert-primary mb-2" role="alert">

</div>


<section id="accordion-with-margin">
  <div class="row">
    <div class="col-sm-12">
      <div class="card collapse-icon accordion-icon-rotate">

        <div class="card-body">

          <div class="accordion" id="accordionPolicyText">


            <div id="policy_text_enable" name="policy_text_enable">
              <div class="collapse-margin">
                <div class="card-header" id="accordionPolicyTextheadingOne" data-toggle="collapse" role="button" data-target="#accordionPolicyTextcollapseOne" aria-expanded="false" aria-controls="accordionPolicyTextcollapseOne">
                  <span class="lead collapse-title font-small-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enter beneficiary details here, text typed here will show on schedule">
                    Beneficiary / Trustee Details
                  </span>
                </div>

                <div id="accordionPolicyTextcollapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionPolicyText" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enter Excess for Motor Policies/Currency Conversion here. Text typed here will show on schedule">
                  <div class="card-body">


                    <div class="table-responsive">
                      <header class="panel-heading font-bold">

                      </header>
                      <table id="beneficiaryTable" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover mb-0 font-small-2 text-center">
                        <thead>
                          <tr>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Relationship</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Benefit Split</th>
                            <th>Created By</th>
                            <th>Created On</th>

                            <th><a href="#new-beneficiary" class="bootstrap-modal-form-open badge badge-pill badge-glow badge-success mr-1 mb-1 pull-right text-default" data-toggle="modal">+
                                Add Beneficiary</a></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>


                  </div>
                </div>
              </div>
              <div class="collapse-margin">
                <div class="card-header" id="accordionPolicyTextheadingTwo" data-toggle="collapse" role="button" data-target="#accordionPolicyTextcollapseTwo" aria-expanded="false" aria-controls="accordionPolicyTextcollapseTwo">
                  <span class="lead collapse-title font-small-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enter mandate details here">
                    Mandate Details
                  </span>
                </div>
                <div id="accordionPolicyTextcollapseTwo" class="collapse" aria-labelledby="accordionPolicyTextheadingTwo" data-parent="#accordionPolicyText" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enter mandate details here">
                  <div class="card-body">
                    <div class="table-responsive">
                      <header class="panel-heading font-bold">

                      </header>
                      <table id="mandateTable" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-hover mb-0 font-small-2 text-center">
                        <thead>
                          <tr>
                            <th>Employer Organisation / Bank</th>
                            <th>Employee / Account Number</th>
                            <th>Premium</th>
                            <th>Created By</th>
                            <th>Created On</th>

                            <th><a href="#new-mandate" onclick="setMandatePremium()" class="bootstrap-modal-form-open badge badge-pill badge-glow badge-success mr-1 mb-1 pull-right text-default" data-toggle="modal">+
                                Add Mandate</a></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>





            <div id="coinsurance" name="coinsurance">


              <div class="collapse-margin">
                <div class="card-header" id="accordionPolicyTextheadingFour" data-toggle="collapse" role="button" data-target="#accordionPolicyTextcollapseFour" aria-expanded="false" aria-controls="accordionPolicyTextcollapseFour">
                  <span class="lead collapse-title font-small-3">
                    <code> CO / RI Inwards Details </code>
                  </span>
                </div>
                <div id="accordionPolicyTextcollapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionPolicyText">
                  <div class="card-body">


                    <section class="panel panel-default">

                      <div class="content-body">


                        <div class="row">
                          <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('cedant_rate') ? ' has-error' : ''}}">
                              <label> CO / RI Rate (%)</label>
                              <input type="text" rows="3" class="form-control" id="cedant_rate" name="cedant_rate" value="{{ Request::old('cedant_rate') ?: '' }}">
                            </div>
                          </div>


                          <div class="col-sm-9">
                            <div class="form-group{{ $errors->has('cedant') ? ' has-error' : ''}}">
                              <label>Cedant</label>
                              <input type="text" rows="3" class="form-control" id="cedant" name="cedant" value="{{ Request::old('cedant') ?: '' }}">
                            </div>
                          </div>


                          <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('cedant_si') ? ' has-error' : ''}}">
                              <label>Cedant SI</label>
                              <input type="text" rows="3" class="form-control" id="cedant_si" name="cedant_si" value="{{ Request::old('cedant_si') ?: '' }}">
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('cedant_policy_number') ? ' has-error' : ''}}">
                              <label>Cedant Policy #</label>
                              <input type="text" rows="3" class="form-control" id="cedant_policy_number" name="cedant_policy_number" value="{{ Request::old('cedant_policy_number') ?: '' }}">
                            </div>
                          </div>


                          <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('cedant_cession_number') ? ' has-error' : ''}}">
                              <label>Cedant Cession #</label>
                              <input type="text" rows="3" class="form-control" id="cedant_cession_number" name="cedant_cession_number" value="{{ Request::old('cedant_cession_number') ?: '' }}">
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('cedant_business') ? ' has-error' : ''}}">
                              <label>Reciprocal Business</label>
                              <input type="text" rows="3" class="form-control" id="cedant_business" name="cedant_business" value="{{ Request::old('cedant_business') ?: '' }}">
                            </div>
                          </div>

                        </div>
                      </div>
                    </section>


                  </div>
                </div>
              </div>




              <div class="collapse-margin">
                <div class="card-header" id="accordionPolicyTextheadingFive" data-toggle="collapse" role="button" data-target="#accordionPolicyTextcollapseFive" aria-expanded="false" aria-controls="accordionPolicyTextcollapseFive">
                  <span class="lead collapse-title font-small-3">
                    <code> Coinsurance Settings </code>
                  </span>
                </div>
                <div id="accordionPolicyTextcollapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionPolicyText">
                  <div class="card-body">

                    <!-- <tr>
                                                <td>Our Coinsurance Share % </td>
                                                <td>
                                                  <div class="col-sm-12">
                                                    <input type="text" class="form-control" value="100" id="reinsurance_rate" name="reinsurance_rate"
                                                      onchange="computeCoinsuranceShare()">
                                                  </div>
                                                </td>
                                              </tr>
                    
                                              <tr>
                                                <td>Our Coinsurance Share (SI)</td>
                                                <td>
                                                  <div class="col-sm-12">
                                                    <input type="text" class="form-control" value="0" id="reinsurance_SI" name="reinsurance_SI" readonly="true">
                                                  </div>
                                                </td>
                                              </tr> -->

                  </div>
                </div>
              </div>

            </div>
            <!-- End Coinsurance  -->

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Accordion with margin end -->

<section id="accordion-with-margin">
  <div class="row">

    <div class="col-sm-12">
      <div class="alert alert-primary mb-2" role="alert">

      </div>
      <div class="card collapse-icon accordion-icon-rotate">

        <div class="card-body">

          <div class="accordion" id="accordionPremium">
            <div class="collapse-margin">
              <div class="card-header" id="headingThree" data-toggle="collapse" role="button" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <span class="lead collapse-title font-small-3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to expand for premium break down">
                  Charges & Discounts
                </span>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionPremium" data-toggle="tooltip" data-placement="top" title="" data-original-title="Text typed here is for internal communication">
                <div class="card-body">
                  <div id="motorcharges" name="motorcharges">
                    <input type="hidden" id="myrisk" name="myrisk" value="{{ Request::old('myrisk') ?: '' }}">
                    <input type="hidden" id="myriskclass" name="myriskclass" value="{{ Request::old('myriskclass') ?: '' }}">

                    <table class="table table-striped table-hover mb-0 font-small-3">
                      <thead>
                        <tr>

                          <th></th>

                          <th>Charged</th>
                        </tr>
                      </thead>
                      <tbody>

                        <tr>
                          <td>Sum Insured</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('suminsured') ?: '' }}" id="suminsured" name="suminsured">
                            </div>
                          </td>
                        </tr>




                        <tr>
                          <td>Own Damage</td>
                          <td>

                            <div class="col-sm-6">
                              A <input type="text" class="form-control" readonly="true" value="{{ Request::old('owndamage') ?: '' }}" id="owndamage" name="owndamage">
                            </div>

                          </td>
                        </tr>




                        <tr>
                          <td>Third party Basic</td>
                          <td>
                            <div class="col-sm-6">
                              A <input type="text" class="form-control" readonly="true" value="{{ Request::old('tpbasic') ?: '' }}" id="tpbasic" name="tpbasic">
                            </div>

                          </td>

                        </tr>

                        <tr>
                          <td>Age & Cubic Capacity Charge</td>
                          <td>

                            <div class="col-sm-6">
                              A
                              <input type="text" class="form-control col-lg-4" readonly="true" value="{{ Request::old('ccage') ?: '' }}" id="ccage" name="ccage">
                            </div>

                          </td>
                        </tr>


                        <tr>
                          <td>Excess Bought</td>
                          <td>

                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('execessbought') ?: '' }}" id="execessbought" name="execessbought">

                              <input type="hidden" class="form-control" readonly="true" value="{{ Request::old('excess_charge_rate') ?: '' }}" id="excess_charge_rate" name="excess_charge_rate">
                            </div>
                          </td>
                        </tr>





                        <tr>
                          <td><span class="label label-info">Office Premium</span></td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('officepremium') ?: '' }}" id="officepremium" name="officepremium">
                            </div>
                          </td>

                        </tr>


                        <tr>
                          <td> <span class="label label-success">Less No Claim Discount</span> </td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('ncd') ?: '' }}" id="ncd" name="ncd">
                            </div>
                          </td>



                        </tr>


                        <tr>
                          <td><span class="label label-warning">Less Fleet Discount </span> </td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('fleet') ?: '' }}" id="fleet" name="fleet">
                            </div>
                          </td>


                        </tr>




                        <tr>
                          <td>Other Loadings </td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('loading') ?: '' }}" id="loading" name="loading">
                            </div>
                          </td>
                        </tr>


                        <tr>
                          <td>Motor Sticker </td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('contribution') ?: '' }}" id="contribution" name="contribution">
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <td>Documentation Fee </td>
                          <td>
                            <div class="col-sm-12">
                              <input type="number" min="0" class="form-control" onkeyup="computeMotorDiscount()" value="0" id="motor_documentation" name="motor_documentation">
                            </div>
                          </td>
                        </tr>




                        <tr>
                          <td>Gross Premium</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('gross_premium') ?: '' }}" id="gross_premium" name="gross_premium">
                            </div>
                          </td>
                        </tr>




                        <tr>
                          <td> Premium Due</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('premium_due_motor') ?: '' }}" id="premium_due_motor" name="premium_due_motor">
                            </div>
                          </td>

                        </tr>


                        <tr>
                          <td>Discount (%)</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="number" min="0" max="80" @if($customers->account_type=='Staff') @else readonly @endif class="form-control" onkeyup="computeMotorDiscount()" value="0" id="motor_discount" name="motor_discount">
                            </div>
                          </td>
                        </tr>



                        <tr>
                          <td> Premium Payable</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" value="{{ Request::old('premium_due_motor_final') ?: '' }}" id="premium_due_motor_final" name="premium_due_motor_final">
                            </div>
                          </td>
                        </tr>





                        <tr>
                          <td>Base Commission (%)</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" value="{{ Request::old('commission_rate_motor') ?: '' }}" id="commission_rate_motor" name="commission_rate_motor">
                            </div>
                          </td>

                        </tr>








                      </tbody>
                    </table>

                  </div>


                  <div id="nonmotorcharges" name="nonmotorcharges">

                    <table class="table table-striped table-hover mb-0 font-small-3">
                      <thead>
                        <tr>

                          <th></th>
                          <th>Charge</th>

                        </tr>
                      </thead>
                      <tbody>

                        <tr>
                          <td>Sum Insured</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('suminsured_2') ?: '' }}" id="suminsured_2" name="suminsured_2">
                            </div>

                          </td>
                        </tr>

                        <tr>
                          <td>Net Annual Premium</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('gross_premium_non_motor') ?: '' }}" id="gross_premium_non_motor" name="gross_premium_non_motor">
                            </div>
                          </td>

                        </tr>

                        <tr>
                          <td> Premium Due</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" readonly="true" value="{{ Request::old('premium_due_non_motor') ?: '' }}" id="premium_due_non_motor" name="premium_due_non_motor">
                            </div>
                          </td>
                        </tr>


                        <tr>
                          <td>Discount (%)</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="number" min="0" max="80" class="form-control" onkeyup="computeNonMotorDiscount()" value="0" id="non_motor_discount" name="non_motor_discount">
                            </div>
                          </td>
                        </tr>


                        <tr>
                          <td>Levy (%)</td>
                          <td>
                            <div class="col-sm-6">
                              <input type="number" class="form-control" onkeyup="computeNonMotorDiscount()" value="0" id="non_motor_levy" name="non_motor_levy">
                            </div>
                            <div class="col-sm-6">
                              <input type="number" class="form-control" readonly="true" value="{{ Request::old('non_motor_levy_charge') ?: '' }}" id="non_motor_levy_charge" name="non_motor_levy_charge">
                            </div>
                          </td>
                        </tr>


                        <tr>
                          <td>Lumpsum Contribution</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="number" class="form-control" onkeyup="computeNonMotorDiscount()" value="0" id="non_motor_sticker" name="non_motor_sticker">
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <td>Documentation Fee</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="number" min="0" class="form-control" onkeyup="computeNonMotorDiscount()" value="0" id="non_motor_documentation" name="non_motor_documentation">
                            </div>
                          </td>
                        </tr>



                        <tr>
                          <td> Premium Payable</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" value="{{ Request::old('premium_due_non_motor_final') ?: '' }}" id="premium_due_non_motor_final" name="premium_due_non_motor_final">
                            </div>
                          </td>
                        </tr>




                        <tr>
                          <td>Base Commission (%)</td>
                          <td>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" value="{{ Request::old('commission_rate_non_motor') ?: '' }}" id="commission_rate_non_motor" name="commission_rate_non_motor">
                            </div>
                          </td>
                        </tr>





                      </tbody>
                    </table>

                    <br>
                    <br>
                  </div>



                </div>
              </div>
            </div>



          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Accordion with margin end -->
<div class="btn-group pull-right">

  {!! csrf_field() !!}
  <div class="doc-buttons">

    <!--                    
                  <button type="button" onclick="savePolicy()" id="master_save" name="master_save" class="btn btn-sm btn-primary ml-50 mb-50 mb-sm-0 cursor-pointer waves-effect waves-light"><i class=""></i> Save </button>
      -->

    <input type="hidden" name="ReferenceNumber" id="ReferenceNumber" value="{{ Request::old('ReferenceNumber') ?: '' }}">

    <button type="button" onclick="printVehicleSchedule()" id="btn_view_schedule" name="btn_view_schedule" class="btn btn-sm btn-success ml-50 mb-50 mb-sm-0 cursor-pointer waves-effect waves-light"><i class=""></i> View Schedule </button>

    <button type="button" onclick="sameVehicleAsk()" id="btn_add_extra" name="btn_add_extra" class="btn btn-sm btn-primary ml-50 mb-50 mb-sm-0 cursor-pointer waves-effect waves-light"><i class=""></i> Add Another Vehicle </button>

  </div>

  <br>
  <br>


</div>




</fieldset>




<!-- Step 3 -->






</form>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Form wizard with step validation section end -->

</div>
</div>
</div>
<!-- END: Content-->


@permission('add-mandate')
<div class="modal fade" id="new-mandate" tabindex="-1" role="dialog" aria-labelledby="new-mandate" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add-drug">Mandate Details(s)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="bootstrap-modal-form" data-validate="parsley" method="post" action="/update-receipt-entry">
          @include('policy/mandate')

          <input type="hidden" name="_token" value="{{ Session::token() }}">
        </form>
      </div>
    </div>
  </div>
</div>
@endpermission

@permission('add-beneficiary')
<div class="modal fade" id="new-beneficiary" tabindex="-1" role="dialog" aria-labelledby="update-stock" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add-drug">Beneficiary Detail(s)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="bootstrap-modal-form" data-validate="parsley" method="post" action="/update-receipt-entry">
          @include('policy/beneficiary')

          <input type="hidden" name="_token" value="{{ Session::token() }}">
        </form>
      </div>
    </div>
  </div>
</div>
@endpermission

@stop



<style>
  .row1 {
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
  }

  .radio-btn-div {
    margin-right: 5%;
  }
</style>

<script src="{{ asset('/event_components/jquery.min.js')}}"></script>
<script src="{{ asset('/js/tinymce/tinymce.min.js')}}"></script>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>





<script type="text/javascript">
  $(document).ready(function() {

    loadWorkgroups();
    showAccidentMeansOfConveyance();

    $("#expiry_date").prop("disabled", true);
    disableUpperLowerTextform();


    $('#funeral_benefit').number(true, 2);
    $('#vehicle_value').number(true, 2);
    $('#item_value').number(true, 2);
    $('#contract_sum').number(true, 2);
    $('#bond_sum_insured').number(true, 2);
    $('#engineering_si').number(true, 2);
    $('#accident_si').number(true, 2);
    $('#marine_sum_insured').number(true, 2);
    $('#liability_si').number(true, 2);


    //  Motor Premium Formatting
    $('#suminsured').number(true, 2);
    $('#owndamage').number(true, 2);
    $('#tpbasic').number(true, 2);
    $('#ccage').number(true, 2);
    $('#ncd').number(true, 2);
    $('#fleet').number(true, 2);
    $('#loading').number(true, 2);
    $('#contribution').number(true, 2);
    $('#gross_premium').number(true, 2);
    $('#officepremium').number(true, 2);
    $('#premium_due_motor').number(true, 2);
    $('#premium_due_motor_final').number(true, 2);


    // Non  Motor Premium Formatting
    $('#suminsured_2').number(true, 2);
    $('#gross_premium_non_motor').number(true, 2);
    $('#premium_due_non_motor').number(true, 2);
    $('#premium_due_non_motor_final').number(true, 2);















    $('#motor_misc_details').hide();
    $('#general_details').hide();
    $('#general_introducer').hide();

    $('#engineering_risk_text').hide();
    $('#bonddate').hide();
    $('#custom_bonds').hide();
    $('#performance_bonds').hide();
    $('#fireinsurance').hide();
    $('#motorinsurancecomprehensive').hide();
    $('#collision_div').hide();
    $('#marineinsurance').hide();
    $('#bondinsurance').hide();
    $('#travelinsurance').hide();
    $('#personalaccidentinsurance').hide();
    $('#liabilityinsurance').hide();
    $('#generalaccident').hide();
    $('#contractorallrisk').hide();
    $('#healthinsurance').hide();
    $('#lifeinsurance').hide();
    $('#motorcharges').hide();
    $("motorcharges").prop("disabled", true);
    showFireblanket();

    $('#pa_activities').select2();
    $('#marine_country_of_importation').select2();
    $('#marine_country_of_destination').select2();
    $('#marine_means_of_conveyance').select2();
    $('#bond_template').select2();


    $('#vehicle_registration_number').keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

    $('#marine_carrier').select2({
      tags: true
    });

    $('#marine_port_of_loading').select2({
      tags: true
    });
    $('#marine_port_of_destination').select2({
      tags: true
    });

    $('#policy_interest').select2({
      tags: true
    });

    $('#property_type').select2({
      tags: true
    });

    $('#property_type_item').select2({
      tags: true
    });

    $('#fire_risk_covered_sub').select2({
      tags: true
    });



    //$('#liability_unit').select2();

    // $('#accident_unit').select2();


    $('#liability_unit').select2({
      tags: true
    });

    $('#accident_unit').select2({
      tags: true
    });

    // $('#policy_interest').select2({
    //   tags: true
    //   });

    $('#vehicle_model').select2({
      tags: true
    });

    $('#vehicle_make').select2({
      tags: true
    });

    $('#vehicle_colour').select2({
      tags: true
    });

    $('#vehicle_body_type').select2({
      tags: true
    });

    $('#account_manager').select2();

    $('#introducer').select2({
      tags: true
    });

    $('#fire_peril').select2({
      tags: true
    });

    // $('#sticker_number').select2({
    // tags: true
    // });

    $('#brown_card_number').select2({
      tags: true
    });

    $('#roofed_with').select2({
      tags: true
    });

    $('#policy_clause').select2({
      tags: true
    });

    $('#marine_country_of_importation').select2({
      tags: true
    });

    $('#engineering_unit').select2({
      tags: true
    });

    $('#engineering_risk_type').select2();


    $('#walled_with').select2({
      tags: true
    });

    $('#agency_commission_motor_2').select2({
      tags: true
    });

    $('#agency_commission_motor_3').select2({
      tags: true
    });

    $('#agency_commission_motor_4').select2({
      tags: true
    });


    $('#policy_workgroup').select2({
      tags: true
    });


    $('#agency_commission_non_motor_2').select2({
      tags: true
    });

    $('#agency_commission_non_motor_3').select2({
      tags: true
    });

    $('#agency_commission_non_motor_4').select2({
      tags: true
    });

    //$('#customer_number').select2();
    $('#vehicle_body_type').select2({
      tags: true
    });
    //$('#policy_clause').select2();
    $('#vehicle_make').select2({
      tags: true,
    });
    $('#vehicle_model').select2({
      tags: true
    });







    $('#policy_product').select2();
    $('#policy_branch').select2();
    $('#destination_country').select2();
    $('#agency').select2();
    $('#policy_currency').select2();
    $('#policy_workgroup').select2();
    $('#policy_sales_type').select2();
    $('#payment_status').select2();
    $('#policy_sales_channel').select2();

    $('#bond_risk_type').select2();
    $('#bond_risk_type_class').select2();
    $('#fire_risk_covered').select2();
    $('#car_risk_type').select2();
    $('#accident_risk_type').select2();
    $('#liability_risk_type').select2();


    $('#preferedcover').select2();
    //$('#vehicle_use').select2();
    $('#vehicle_ncd').select2();
    // $('#charge_type').select2();
    $('#exclude_loading').select2();
    $('#vehicle_fleet_discount').select2();
    $('#vehicle_risk').select2();
    $('#vehicle_make_year').select2();

    $('#travel_product_name').select2();
    $('#travel_product_plan').select2();
    $('#travel_country_of_dest').select2();

    loadInsurer();
    setPeriodDays();



  });
</script>



<script>
  tinymce.init({
    selector: '#policy_upper_text',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
  });
</script>


<script>
  tinymce.init({
    selector: '#policy_lower_text',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });


  tinymce.init({
    selector: '#property_description',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });


  tinymce.init({
    selector: '#engineering_risk_description',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });

  tinymce.init({
    selector: '#accident_risk_description',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });

  tinymce.init({
    selector: '#marine_condition',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });

  tinymce.init({
    selector: '#liability_risk_description',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });
</script>

<script>
  tinymce.init({
    selector: '#policy_renewal_text',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });
</script>


<script>
  tinymce.init({
    selector: '#policy_end_text',
    height: 300,
    menubar: true,
    allow_conditional_comments: true,
    allow_html_in_named_anchor: true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code help wordcount',
      'template'
    ],
    toolbar: 'insert | undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

  });
</script>





<script type="text/javascript">
  function notbuilding() {
    if ($('#property_type').val() != "Public Liability") {
      $('#notproperty').show();
      // $('#fire_sticker').show();
      var cpi = 0;
      //var levy = '1';
      //alert(cpi);
      $('#masterform input[name="collapserate"]').val(cpi);
      //$('#masterform input[name="levy_rate"]').val(cpi);
    } else if ($('#property_type').val() == "Commercial Property" || $('#property_type').val() == "Commercial Building") {
      $('#notproperty').show();
      $('#fire_sticker').show();
      var cpi = 0;
      //var levy = '1';
      //alert(cpi);
      $('#masterform input[name="collapserate"]').val(cpi);
      //$('#masterform input[name="levy_rate"]').val(cpi);

    } else {
      var cpi = 0;
      $('#masterform input[name="collapserate"]').val(cpi);
      $('#notproperty').hide();
      $('#fire_sticker').hide();
    }

  }

  function computeLoading() {
    var premium = isNaN(parseInt($("#item_value").val() * $("#fire_rate").val() / 100)) ? 0 : ($("#item_value").val() * $("#fire_rate").val() / 100)
    var collapsecharge = isNaN(parseInt($("#item_value").val() * $("#collapserate").val() / 100)) ? 0 : ($("#item_value").val() * $("#collapserate").val() / 100)


    //renovationcharge = (txtsuminsured.Value * (txtrenovationcharge.Value / 100));
    //publicliabilitycharge = (PlLimit.Value * (PLrate.Value / 100));
    //txtloading2 = txtbasicpremium.Value + propertydamagelimit.Value;


    var ltadiscount = (premium * $("#lta").val() / 100)
    var fhdiscount = (premium * $("#fire_hydrant").val() / 100)
    var fireexdiscount = (premium * $("#fire_extinguisher").val() / 100)
    var staffdiscount = (premium * $("#staff_discount").val() / 100)

    //var totaldiscount   = ltadiscount + fhdiscount + fireexdiscount + staffdiscount;
    //var netpremium =  premium + collapsecharge - totaldiscount;

    //alert(totaldiscount);

    $("#firepremium").val(premium);
    //$("#firepremium2").val(netpremium);


  }

  function addPatient() {

  }

  function hideSaveButton() {
    var saveBtnElement = $('a[href="#finish"]');
    saveBtnElement.hide();
  }

  function showSaveButton() {
    var saveBtnElement = $('a[href="#finish"]');
    saveBtnElement.show();
    saveBtnElement.css('visibility', 'visible')
  }

  function savePolicy() {

    //$("#master_save").prop("disabled", true);

    swal("Please wait while policy details get saved!", "info.", "warning");

    if ($('#policy_product').val() == "Motor Insurance") {

      //askToSave();
      addExtraClauses();
      //swal("Please wait while vehicle details get saved!", "info.", "warning"); 
      // addMotorDetails();
      if ($('#premium_due_motor_final').val() != "") {
        swal("Please wait while vehicle details get saved!", "info.", "warning");
        addMotorDetails();
      } else {
        swal("Please ensure premium has been computed correctly and try again!", "info.", "warning");
      }


    } else if ($('#policy_product').val() == "Fire Insurance") {


      saveNonMotorPolicy();

    } else if ($('#policy_product').val() == "Bond Insurance") {
      saveNonMotorPolicy();
    } else if ($('#policy_product').val() == "Marine Insurance") {

      saveNonMotorPolicy();

    } else if ($('#policy_product').val() == "Engineering Insurance") {

      saveNonMotorPolicy();

    } else if ($('#policy_product').val() == "General Accident Insurance") {
      saveNonMotorPolicy();
    } else if ($('#policy_product').val() == "Liability Insurance") {
      saveNonMotorPolicy();
    } else if ($('#policy_product').val() == "Travel Insurance") {
      saveNonMotorPolicy();
    } else {
      saveNonMotorPolicy();
    }


  }

  //new function to add compulsory clauses 
  function addCompulsoryClauses() {

    switch ($('#policy_product').val()) {
      case 'Motor Insurance':

        $('#policy_clause option:contains(M999-Motor Compulsory Clauses)').prop("selected", true).change();
        break;
      case 'Fire Insurance':
        $('#policy_clause option:contains(F999-Fire Compulsory Clauses)').prop("selected", true).change();
        break;
      case 'Travel Insurance':
        $('#policy_clause option:contains(T001-Travel Compulsory Clauses)').prop("selected", true).change();
        break;
      default:
        // code block
    }
  }



  //new function to properly add increase in tppdl value clause 
  function addExtraClauses() {
    if ($('#vehicle_tppdl_value').val() > 6000) {
      $('#policy_clause option:contains(M014-Increase Of Third Party Property Damage Limit (Pm 44))').prop("selected", true).change();
    } else {
      $('#policy_clause option:contains(M014-Increase Of Third Party Property Damage Limit (Pm 44))').prop("selected", false).change();
    }
  }



  function computeMotorPremium() {

    if ($('#charge_type').val() == "New Rating") {
      computePremiumNewTariff();

      /*
       if($('#vehicle_tppdl_value').val() > 6000)
       {
         var $newOption = $("<option selected='selected'></option>").val('"<li><b>INCREASE OF THIRD PARTY PROPERTY DAMAGE LIMIT (PM 44)</b><br><p>It is hereby understood and agreed that the limit of the amount of the Companys liability under this policy in respect of any one claim or series of claims arising out of one event is increased to *GHC..............* (See below)Subject otherwise to the terms, condition and exception of this policy</p></li>"').text("M014-Increase Of Third Party Property Damage Limit (Pm 44)")
        $("#policy_clause").append($newOption).trigger('change');
       }
       */

    } else {
      //computePremiumOldTariff();
      computePremiumNewTariff();
    }

  }

  function computeShortPeriodMotor(shortperiodtype) {
    var myrating = shortperiodtype.value;

    $.get('/get-commute-shortperiod', {

        "commence_date": $('#commence_date').val(),
        "shortperiod": myrating,
        "gross_premium": $('#gross_premium').val()

      },
      function(data) {

        $.each(data, function(key, value) {

          $('#premium_due_motor').val(data.premium_due);

          $('#premium_due_motor_final').val(data.premium_due);
          $('#expiry_date').val(data.period_to);

        });

      }, 'json');

  }

  function computeNonMotorDiscount() {


    var discount_rate = parseFloat($('#non_motor_discount').val());
    var premium_due = parseFloat($('#premium_due_non_motor').val());

    var levy_rate = parseFloat($('#non_motor_levy').val());
    var non_motor_sticker = parseFloat($('#non_motor_sticker').val());
    var non_motor_documentation = parseFloat($('#non_motor_documentation').val());

    var discount = 0;
    var premium_payable = 0;
    var levy_charge = 0;

    discount = (discount_rate / 100) * premium_due;
    levy_charge = (levy_rate / 100) * premium_due;

    premium_payable = ((premium_due - discount) + (levy_charge + non_motor_sticker + non_motor_documentation));

    $('#premium_due_non_motor_final').val(premium_payable);
    $('#non_motor_levy_charge').val(levy_charge);

  }


  function computeMotorDiscount() {


    var discount_rate_m = parseFloat($('#motor_discount').val());
    var premium_due_m = parseFloat($('#premium_due_motor').val());
    var motor_documentation_m = parseFloat($('#motor_documentation').val());

    var discount_m = 0;
    var premium_payable_m = 0;
    var motor_documentation_charge = motor_documentation_m;


    discount_m = (discount_rate_m / 100) * premium_due_m;

    premium_payable_m = ((premium_due_m - discount_m) + motor_documentation_charge);
    $('#premium_due_motor_final').val(premium_payable_m);

  }


  function getChassisDetails() {
    var vin_number = $('#vehicle_chassis_number').val();
    //console.log(vin_number.length);
    if (vin_number.length == 17) {
      $.get(`/get-chassis-details/${vin_number}`,
        function(data) {
          //console.log(data);

          if (data['status'] == 'success') {
            $.each(data, function(key, value) {

              {
                swal(`Vehicle details \n Make: ${data.Make}\n Model: ${data.Model}\n Vehicle Type: ${data.Vehicle_Type}\n Body Type: ${data.Body_Class}`);
              }

              //$('#commission_rate_non_motor').val(data.commission);

            });
          }


        }, 'json');
    }
  }


  //new code to check the tppdl value 
  function checkTppdlValue() {
    if ($('#vehicle_tppdl_value').val() < 6000) {

      swal("Please tppdl shouldn't be less than 6000", 'Fill all fields', "error");
      $('#vehicle_tppdl_value').val(6000);

    }

    computenonMotorPremium();

  }

  function checkCollisionValue() {
    if ($('#collision_value').val() < 6000) {
      swal("Please collision limit amount shouldn't be less than 6000", 'Fill all fields', "error");
      $('#collision_value').val(6000);
    } else if ($('#collision_value').val() > 6000 && $('#collision_value').val() < 10000) {
      swal("Please collision limit amount shouldn either be 6000 or 10000", 'Fill all fields', "error");
      $('#collision_value').val(6000);
    } else if ($('#collision_value').val() > 10000) {
      swal("Please collision limit amount cannot be greater than 10000", 'Fill all fields', "error");
      $('#collision_value').val(10000);
    }

    computenonMotorPremium();
  }



  function computeShortPeriodNonMotor(shortperiodtype) {
    var myrating = shortperiodtype.value;

    if (myrating == "Pro Rata")

    {
      $("#expiry_date").prop("disabled", true);
    } else {

      $.get('/get-commute-shortperiod', {

          "commence_date": $('#commence_date').val(),
          "shortperiod": myrating,
          "gross_premium": $('#gross_premium_non_motor').val()

        },
        function(data) {

          $.each(data, function(key, value) {

            $('#premium_due_non_motor').val(data.premium_due);
            $('#premium_due_non_motor_final').val(data.premium_due);
            $('#expiry_date').val(data.period_to);
            $("#expiry_date").prop("disabled", true);

            computenonMotorPremium();

          });

        }, 'json');
    }

  }


  function computenonMotorPremium() {


    $('#master_save').show();
    $('#btn_view_schedule').hide();
    $('#btn_add_extra').hide();
    $("#master_save").prop("disabled", false);


    if ($('#policy_product').val() == "Fire Insurance") {

      // var total=isNaN(parseInt($("#fire_rate").val()/100 * $("#item_value").val())) ? 0 :($("#item_value").val() * $("#fire_rate").val()/100)
      // $("#gross_premium_2").val(total);
      // var suminsured = $("#item_value").val();
      // $("#suminsured_2").val(suminsured);

      var risk = $("#fire_risk_covered").val();
      $("#myrisk").val(risk);


      getCommulativeFirePremiumSI();


    } else if ($('#policy_product').val() == "Motor Insurance" & $('#charge_type').val() != "") {

      if ($('#vehicle_use').val() != "" & $('#preferedcover').val() != "" & $('#vehicle_risk').val() != "" & $('#vehicle_buy_back_excess').val() != "" & $('#vehicle_tppdl_value').val() != 0 & $('#vehicle_make_year').val() != "" & $('#vehicle_cubic_capacity').val() != 0)

      {
        computeMotorPremium();
      } else {

      }




    } else if ($('#policy_product').val() == "Bond Insurance") {



      var risk = $("#bond_risk_type").val();
      var riskclass = $("#bond_risk_type_class").val();
      //myrisk
      $("#myrisk").val(risk);
      $("#myriskclass").val(riskclass);
      getCommulativeFirePremiumSI();


    } else if ($('#policy_product').val() == "Marine Insurance") {

      var risk = $("#marine_risk_type").val();
      $("#myrisk").val(risk);

      getCommulativeFirePremiumSI();

    } else if ($('#policy_product').val() == "Engineering Insurance") {

      var risk = $("#engineering_risk_type").val();
      $("#myrisk").val(risk);

      getCommulativeFirePremiumSI();

    } else if ($('#policy_product').val() == "General Accident Insurance") {

      var risk = $("#accident_risk_type").val();
      $("#myrisk").val(risk);

      getCommulativeFirePremiumSI();
    } else if ($('#policy_product').val() == "Liability Insurance") {

      var risk = $("#liability_risk_type").val();
      $("#myrisk").val(risk);

      getCommulativeFirePremiumSI();
    } else if ($('#policy_product').val() == "Travel Insurance") {
      computeTravelPremium();
    } else {

      var risk = $("#liability_risk_type").val();
      $("#myrisk").val(risk);

      getCommulativeFirePremiumSI();
    }



  }


  function computeTravelPremium() {

    if ($('#travel_product_name').val() !== "" && $('#travel_product_plan').val() != "" && ($('#travel_country_of_dest').val() != "" || $('#travel_country_of_dest').val() == null)) {
      $.get('/get-mapfre-prices', {
          "commencement_date": $('#commence_date').val(),
          "enddate": $('#expiry_date').val(),
          "travel_product_name": $('#travel_product_name').val(),
          "travel_country_of_dest": $('#travel_country_of_dest').val(),
          "customer_number": $('#customer_number').val(),
          "passport_number": $('#travel_passport_number').val(),
          "policy_currency": $('#policy_currency').val(),
          "travel_product_plan": $('#travel_product_plan').val(),
          "policy_number": $('#policy_number').val(),
          "policy_minor_holder_status": $('#policy_minor_holder_status').is(":checked")
        },
        function(data) {

          $('#suminsured_2').val(0);
          $('#non_motor_discount').val(0);
          $('#motor_discount').val(0);
          $('#gross_premium_non_motor').val(data.premium);
          $('#premium_due_non_motor').val(data.premium);
          $('#premium_due_non_motor_final').val(data.premium);

          getTotalTravelPremium();




        }, 'json');
    } else {

    }
  }


  function set_default_tppdl(rating) {

    var myrating = rating.value;

    if (myrating == "New Rating") {
      var tppdlnew = 6000;
      var exchange_rate_new = $('#applied_exchange').val();
      //var convertedtppdlnew = tppdlnew; ///exchange_rate_new;
      var convertedtppdlnew = tppdlnew; //exchange_rate_new;
      $('#vehicle_tppdl_value').val(convertedtppdlnew);
      $('#premium_due').val('0');


      var $newOption = $("<option selected='selected'></option>").val('<li class="hide"><b>MOTOR COMPULSORY CLAUSES</b></li>').text("M999-Motor Compulsory Clauses")
      $("#policy_clause").append($newOption).trigger('change');


    } else {
      var tppdlnew = 6000;
      var exchange_rate_new = $('#applied_exchange').val();
      //var convertedtppdlnew = tppdlnew; ///exchange_rate_new;
      var convertedtppdlnew = tppdlnew; //exchange_rate_new;
      $('#vehicle_tppdl_value').val(convertedtppdlnew);
      $('#premium_due').val('0');

      var $newOption = $("<option selected='selected'></option>").val('<li class="hide"><b>MOTOR COMPULSORY CLAUSES</b></li>').text("M999-Motor Compulsory Clauses")
      $("#policy_clause").append($newOption).trigger('change');





    }
  }

  function getCommissionRates(cover) {

    var mycover = cover.value;

    //alert(mycover);
    $.get('/get-commission-non-motor', {

        "policy_product": $('#policy_product').val(),
        "agency": $('#policy_sales_type').val(),
        "cover": mycover,

      },
      function(data) {

        $.each(data, function(key, value) {


          $('#commission_rate_non_motor').val(data.commission);
          $('#mycoverage').val(mycover);
        });

      }, 'json');

  }



  function getCommulativeFirePremiumSI() {


    // alert($('#mycoverage').val());

    if ($('#mycoverage').val() != "") {

      $.get('/get-non-motor-premium', {

          //alert($('#policy_number').val());
          "policy_number": $('#policy_number').val(),
          "policy_product": $('#policy_product').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "issue_date": $('#issue_date').val(),
          "rating_factor": $('#rating_factor').val(),
          "currency": $('#policy_currency').val(),
          "agency": $('#policy_sales_type').val(),
          "cover": $('#mycoverage').val()

        },
        function(data) {

          $.each(data, function(key, value) {

            var unearned = 0;
            $('#suminsured_2').val(data.myfiresuminsured);
            $('#non_motor_discount').val(0);
            $('#motor_discount').val(0);
            $('#gross_premium_non_motor').val(data.myfirepremium);
            $('#premium_due_non_motor').val(data.premiumpayable);
            $('#premium_due_non_motor_final').val(data.premiumpayable);
            $('#myrisk').val(data.myrisk);
            $('#myriskclass').val(data.myriskclass);



          });

        }, 'json');
    } else {

    }


  }


  function recomputeMotor() {


  }

  function generatePolicyNumber() {

    //alert('hello');

    if ($('#policy_number').val() == "") {
      $.get('/generate-policynumber-new', {



          "policy_product": $('#policy_product').val(),


        },
        function(data) {

          $.each(data, function(key, value) {
            //swal("Policy : ", data["policy_number"], "info");
            $('#policy_number').val(data.policy_number);

          });

        }, 'json');
    } else {


    }

  }


  function addPerilRate() {
    if ($('#peril_rate').val() != "") {

      $.get('/add-fire-peril-applied', {
          "policy_number": $('#policy_number').val(),
          "fire_peril": $('#fire_peril').val(),
          "peril_rate": $('#peril_rate').val()
        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Rate successfully saved!");
              loadPerilsApplied();

            } else {
              toastr.success("Rate failed to save!");
            }
          });

        }, 'json');
    } else {
      swal("Please fill appplicable peril details!");
    }
  }


  function addProperty() {
    tinyMCE.triggerSave();
    if ($('#fire_risk_covered').val() == "") {
      document.getElementById('fire_risk_covered').focus();
      swal("Please select risk cover ", 'Fill all fields', "error");
    } else if ($('#property_type').val() == "") {
      document.getElementById('property_type').focus();
      swal("Enter select risk type ", 'Fill all fields', "error");
    } else if ($('#property_number_item').val() == "") {
      document.getElementById('property_number_item').focus();
      swal("Please enter risk number ", 'Fill all fields', "error");
    } else if ($('#property_item_number').val() == "") {
      document.getElementById('property_item_number').focus();
      swal("Please enter item number ", 'Fill all fields', "error");
    } else if ($('#item_value').val() == "") {
      document.getElementById('item_value').focus();
      swal("Please enter risk value ", 'Fill all fields', "error");
    } else if ($('#property_description').val() == "") {
      document.getElementById('property_description').focus();
      swal("Please enter risk description or address ", 'Fill all fields', "error");
    } else if ($('#fire_rate').val() == "") {
      document.getElementById('fire_rate').focus();
      swal("Please enter risk rate ", 'Fill all fields', "error");
    } else if ($('#property_address').val() == "") {
      document.getElementById('property_address').focus();
      swal("Please enter address for item ", 'Fill all fields', "error");
    } else {


      //computeLoading();

      // alert($('#add_up_fire').prop('checked'));

      $.get('/add-property-risk', {
          "policy_number": $('#policy_number').val(),
          "fire_risk_covered": $('#fire_risk_covered').val(),
          "fire_risk_covered_sub": $('#fire_risk_covered_sub').val(),
          "property_type": $('#property_type').val(),
          "property_number": $('#property_number_item').val(),
          "item_number": $('#property_item_number').val(),
          "item_value": $('#item_value').val(),
          "unit_number": $('#unit_number').val(),
          "property_address": $('#property_address').val(),
          "property_description": $('#property_description').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "issue_date": $('#issue_date').val(),
          "longitude_x": $('#longitude_x').val(),
          "longitude_y": $('#longitude_y').val(),
          "add_up_fire": $('#add_up_fire').prop('checked'),
          "survey_number": $('#survey_number').val(),
          "survey_date": $('#survey_date').val(),
          "property_content": $('#property_content').val(),
          "fire_rate": $('#fire_rate').val(),
          "firepremium": $('#firepremium').val(),
          "lta": $('#lta').val(),
          "fire_extinguisher": $('#fire_extinguisher').val(),
          "fire_hydrant": $('#fire_hydrant').val(),
          "staff_discount": $('#staff_discount').val(),
          "collapserate": $('#collapserate').val(),
          "levy_rate": $('#levy_rate').val(),
          "firekey": $('#firekey').val(),
          "endorsement_policy_number": $('#endorsement_policy_number').val(),
          "endorsement_number": $('#endorsement_number').val(),
          "rating_factor": $('#rating_factor').val()

        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {

              toastr.success("Property successfully saved!");
              loadProperties();
              computenonMotorPremium();



              //$('#property_number_item').val('');
              $('#item_value').val('');
              //$('#unit_number').val('');
              $('#property_description').val('');
              $("#btnfire").html('Click to Add New Item');




            } else {
              toastr.success("Property failed to save!");
            }
          });

        }, 'json');
    }

  }



  function addPropertyItem() {
    tinyMCE.triggerSave();
    if ($('#fire_risk_covered').val() == "") {
      document.getElementById('fire_risk_covered').focus();
      swal("Please select risk cover ", 'Fill all fields', "error");
    } else if ($('#property_type_item').val() == "") {
      document.getElementById('property_type_item').focus();
      swal("Enter select item type ", 'Fill all fields', "error");
    } else if ($('#property_number_item').val() == "") {
      document.getElementById('property_number_item').focus();
      swal("Please select risk number ", 'Fill all fields', "error");
    } else if ($('#property_item_number').val() == "") {
      document.getElementById('property_item_number').focus();
      swal("Please enter item number ", 'Fill all fields', "error");
    } else if ($('#item_value_item').val() == "") {
      document.getElementById('item_value_item').focus();
      swal("Please enter item value ", 'Fill all fields', "error");
    } else if ($('#property_description_item').val() == "") {
      document.getElementById('property_description_item').focus();
      swal("Please enter item description ", 'Fill all fields', "error");
    } else if ($('#fire_rate_item').val() == "") {
      document.getElementById('fire_rate_item').focus();
      swal("Please enter item rate ", 'Fill all fields', "error");
    } else {


      computeLoading();

      $.get('/add-property-risk', {
          "policy_number": $('#policy_number').val(),
          "fire_risk_covered": $('#fire_risk_covered').val(),
          "fire_risk_covered_sub": $('#fire_risk_covered_sub').val(),
          "property_type": $('#property_type_item').val(),
          "property_number": $('#property_number_item').val(),
          "item_number": $('#property_item_number').val(),
          "item_value": $('#item_value_item').val(),
          "unit_number": $('#unit_number').val(),
          "property_address": $('#property_address').val(),
          "property_description": $('#property_description_item').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),

          "longitude_x": $('#longitude_x').val(),
          "longitude_y": $('#longitude_y').val(),
          "survey_number": $('#survey_number_item').val(),
          "survey_date": $('#survey_date_item').val(),
          "property_content": $('#property_content').val(),


          "fire_rate": $('#fire_rate_item').val(),
          "firepremium": $('#firepremium').val(),
          "lta": $('#lta').val(),
          "fire_extinguisher": $('#fire_extinguisher').val(),
          "fire_hydrant": $('#fire_hydrant').val(),
          "staff_discount": $('#staff_discount').val(),
          "collapserate": $('#collapserate').val(),
          "levy_rate": $('#levy_rate').val(),
          "rating_factor": $('#rating_factor').val()

        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {

              toastr.success("Item successfully saved!");
              loadProperties();


              //$('#fire_risk_covered').val('');
              $('#property_type_item').val('');
              //$('#property_number_item').val('');
              $('#property_item_number').val('');
              $('#item_value_item').val('');
              //$('#fire_rate_item').val('');

              $('#unit_number').val('');
              $('#property_address').val('');
              $('#property_description_item').val('');




            } else {
              toastr.success("Property failed to save!");
            }
          });

        }, 'json');
    }

  }



  function addBondDetails() {

    tinyMCE.triggerSave();
    if ($('#bond_risk_type').val() == "") {
      document.getElementById('bond_risk_type').focus();
      swal("Please select risk type ", 'Fill all fields', "error");
    } else if ($('#bond_risk_type_class').val() == "") {
      document.getElementById('bond_risk_type_class').focus();
      swal("Please select bond category type ", 'Fill all fields', "error");
    } else if ($('#bond_interest').val() == "") {
      document.getElementById('bond_interest').focus();
      swal("Please enter principal name ", 'Fill all fields', "error");
    } else if ($('#bond_interest_address').val() == "") {
      document.getElementById('bond_interest_address').focus();
      swal("Please enter principal address ", 'Fill all fields', "error");
    } else if ($('#contract_sum').val() == 0) {
      document.getElementById('contract_sum').focus();
      swal("Please enter contract sum ", 'Fill all fields', "error");
    } else if ($('#bond_sum_insured').val() == 0) {
      document.getElementById('bond_sum_insured').focus();
      swal("Please enter bond amount ", 'Fill all fields', "error");
    } else if ($('#bond_rate').val() == 0) {
      document.getElementById('bond_rate').focus();
      swal("Please enter bond rate ", 'Fill all fields', "error");
    } else if ($('#bond_contract_description').val() == "") {
      document.getElementById('bond_contract_description').focus();
      swal("Please enter contract description ", 'Fill all fields', "error");
    } else {



      //alert($('#contract_sum').val());
      $.get('/add-bond-schedule', {
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),
          "bond_risk_type": $('#bond_risk_type').val(),
          "bond_risk_type_class": $('#bond_risk_type_class').val(),
          "bond_interest": $('#bond_interest').val(),
          "bond_rate": $('#bond_rate').val(),
          "bond_interest_address": $('#bond_interest_address').val(),
          "contract_sum": $('#contract_sum').val(),
          "bond_sum_insured": $('#bond_sum_insured').val(),
          "policy_currency": $('#policy_currency').val(),
          "bond_contract_description": $('#bond_contract_description').val(),
          "bondkey": $('#bondkey').val(),

          "bond_declaration_number": $('#bond_declaration_number').val(),
          "bond_serial_number": $('#bond_serial_number').val(),

          "bond_exchange_rate": $('#bond_exchange_rate').val(),
          "valid_until": $('#valid_until').val(),
          "bond_template": $('#bond_template').val(),

          "policy_number": $('#policy_number').val(),
          "rating_factor": $('#rating_factor').val()
        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Bond Schedule successfully saved!");
              loadBondDetails();
              computenonMotorPremium();

              $('#bond_sum_insured').val('0');
              $('#bond_rate').val('0');
              $('#bond_contract_description').val('');
              $("#btnbond").html('Click to Add New Item');




            } else {
              toastr.error("Bond Schedule failed to save!");
            }
          });

        }, 'json');
    }

  }

  /* travel family details addition */
  function addTravelDetails() {
    if ($('#travel_family_first_name').val() == "") {
      document.getElementById('travel_family_first_name').focus();
      swal("Please enter first name", 'Fill all fields', "error");
    } else if ($('#travel_family_last_name').val() == "") {
      document.getElementById('travel_family_last_name').focus();
      swal("Please enter last name", 'Fill all fields', "error");
    } else if ($('#travel_family_gender').val() == "") {
      document.getElementById('travel_family_gender').focus();
      swal("Please enter gender", 'Fill all fields', "error");
    } else if ($('#travel_family_date_of_birth').val() == "") {
      document.getElementById('travel_family_date_of_birth').focus();
      swal("Please enter date of birth", 'Fill all fields', "error");
    } else if ($('#travel_family_passport_number').val() == "") {
      document.getElementById('travel_family_passport_number').focus();
      swal("Please enter passport number", 'Fill all fields', "error");
    } else if ($('#travel_product_name').val() == "") {
      document.getElementById('travel_product_name').focus();
      swal("Please enter product name", 'Fill all fields', "error");
    } else if ($('#travel_product_plan').val() == "") {
      document.getElementById('travel_product_plan').focus();
      swal("Please enter product plan", 'Fill all fields', "error");
    } else if (($('#travel_country_of_dest').val() == "" || $('#travel_country_of_dest').val() == null)) {
      document.getElementById('travel_country_of_dest').focus();
      swal("Please enter country of destination", 'Fill all fields', "error");
    } else {

      $.get('/add-travel-family-details', {

          "policy_number": $('#policy_number').val(),
          "first_name": $('#travel_family_first_name').val(),
          "last_name": $('#travel_family_last_name').val(),
          "gender": $('#travel_family_gender').val(),
          "date_of_birth": $('#travel_family_date_of_birth').val(),
          "passport_number": $('#travel_family_passport_number').val(),

          "commencement_date": $('#commence_date').val(),
          "enddate": $('#expiry_date').val(),
          "travel_product_name": $('#travel_product_name').val(),
          "travel_country_of_dest": $('#travel_country_of_dest').val(),
          "customer_number": $('#customer_number').val(),
          "policy_currency": $('#policy_currency').val(),
          "travel_product_plan": $('#travel_product_plan').val(),
          "policy_number": $('#policy_number').val(),
          "policy_minor_holder_status": $('#policy_minor_holder_status').is(":checked")

        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Family details added successfully!");
              loadTravelDetails();
              getTotalTravelPremium();
              //computenonMotorPremium();
              $("#btntravel").html('Click to Add New Item');

            } else {
              toastr.error("Failed to add family details!");

            }
          });

        }, 'json');
    }

  }


  function loadTravelDetails() {
    $.get('/get-travel-family-details', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {
        $('#travelScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#travelScheduleTable tbody').append('<tr><td>' + value['fullname'] + '</td><td>' + value['gender'] + '</td><td>' + value['date_of_birth'] + '</td><td>' + value['passport_number'] + '</td><td>' + value['premium'] + '</td><td>' + value['created_on'] + '</td><td>' + value['created_by'] + '</td><td><a a href="#"><i onclick="editTravelFamilyDetails(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeTravelFamilyDetails(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }


  function editTravelFamilyDetails(id) {
    $.get("/edit-travel-family-detail", {
        "id": id
      },
      function(json) {

        $('#masterform input[name="travelkey"]').val(json.travelkey);
        $('#masterform select[name="travel_family_gender"]').val(json.gender).select2();
        $('#masterform input[name="travel_family_first_name"]').val(json.first_name);
        $('#masterform input[name="travel_family_last_name"]').val(json.last_name);
        $('#masterform input[name="travel_family_date_of_birth"]').val(json.date_of_birth);
        $('#masterform input[name="travel_family_passport_number"]').val(json.passport_number);

        $('#masterform button[name="btntravel"]').html('Click to Update Item');


        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }


  function removeTravelFamilyDetails(id) {


    swal({
        title: "Are you sure you want to delete family memeber ?",

        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "No, cancel !",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {

          $.get('/delete-travel-family-detail', {
              "ID": id
            },
            function(data) {

              $.each(data, function(key, value) {
                if (value == "OK") {
                  loadTravelDetails();
                  swal.close();
                } else {
                  swal("Cancelled", "Failed to be removed from list.", "error");

                }

              });

            }, 'json');

        } else {
          swal("Cancelled", "Failed to delete family member.", "error");
        }
      });
  }



  function getTotalTravelPremium() {

    $.get('/get-total-travel-premium', {

        "policy_number": $('#policy_number').val(),
      },
      function(data) {

        $.each(data, function(key, value) {

          var minor_travelling = $('#policy_minor_holder_status').is(":checked");
          var premium = 0;


          if (minor_travelling == true) {
            premium = data.premium;
          } else {
            premium = parseFloat($('#gross_premium_non_motor').val().replaceAll(",", "")) + data.premium;
          }

          swal("Premium : " + $('#policy_currency').val() + ' ' + thousands_separators(premium), '', "info");
          $('#premium_due_non_motor').val(premium);
          $('#premium_due_non_motor_final').val(premium);



        });

      }, 'json');
  }


  function addMarineDetails() {

    tinyMCE.triggerSave();
    if ($('#marine_risk_type').val() == "") {
      document.getElementById('marine_risk_type').focus();
      swal("Please select risk cover ", 'Fill all fields', "error");
    } else if ($('#marine_sum_insured').val() == "") {
      document.getElementById('marine_sum_insured').focus();
      swal("Please enter sum insured ", 'Fill all fields', "error");
    } else if ($('#marine_rate').val() == "") {
      document.getElementById('marine_rate').focus();
      swal("Please enter marine rate ", 'Fill all fields', "error");
    } else if ($('#marine_vessel').val() == "") {
      document.getElementById('marine_vessel').focus();
      swal("Please enter vessel name ", 'Fill all fields', "error");
    } else if ($('#marine_voyage').val() == "") {
      document.getElementById('marine_voyage').focus();
      swal("Please enter marine voyage ", 'Fill all fields', "error");
    } else if ($('#marine_condition').val() == "") {
      document.getElementById('marine_condition').focus();
      swal("Please enter conditions subject to", 'Fill all fields', "error");
    } else if ($('#marine_interest').val() == "") {
      document.getElementById('marine_interest').focus();
      swal("Please enter marine interest ", 'Fill all fields', "error");
    } else {

      $.get('/add-marine-schedule', {

          "policy_number": $('#policy_number').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),

          "marine_risk_type": $('#marine_risk_type').val(),
          "marine_means_of_conveyance": $('#marine_means_of_conveyance').val(),
          "marine_sum_insured": $('#marine_sum_insured').val(),
          "marine_rate": $('#marine_rate').val(),

          "marine_valuation": $('#marine_valuation').val(),
          "marine_bill_of_landing": $('#marine_bill_of_landing').val(),

          "marine_vessel": $('#marine_vessel').val(),
          "marine_vessel_number": $('#marine_vessel_number').val(),
          "marine_vessel_flag": $('#marine_vessel_flag').val(),

          "voyage_date": $('#voyage_date').val(),
          "departure_date": $('#departure_date').val(),
          "arrival_date": $('#arrival_date').val(),


          "marine_country_of_importation": $('#marine_country_of_importation').val(),
          "marine_country_of_destination": $('#marine_country_of_destination').val(),
          "marine_carrier": $('#marine_carrier').val(),
          "marine_port_of_loading": $('#marine_port_of_loading').val(),
          "marine_port_of_destination": $('#marine_port_of_destination').val(),

          "marine_interest": $('#marine_interest').val(),
          "marine_condition": $('#marine_condition').val(),


          "marinekey": $('#marinekey').val(),
          "rating_factor": $('#rating_factor').val(),



        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Marine schedule successfully saved!");
              loadMarineDetails();
              computenonMotorPremium();
              $("#btnmarine").html('Click to Add New Item');

            } else {
              toastr.error("Marine schedule failed to save!");

            }
          });

        }, 'json');
    }

  }

  function addEngineeringDetails() {

    tinyMCE.triggerSave();
    if ($('#engineering_risk_type').val() == "") {
      document.getElementById('engineering_risk_type').focus();
      swal("Please select risk type ", 'Fill all fields', "error");
    } else if ($('#car_nature_of_business').val() == "") {
      document.getElementById('car_nature_of_business').focus();
      swal("Please enter nature of business ", 'Fill all fields', "error");
    } else if ($('#engineering_unit').val() == "") {
      document.getElementById('engineering_unit').focus();
      swal("Please select/enter an item  ", 'Fill all fields', "error");
    } else if ($('#engineering_risk_description').val() == "") {
      document.getElementById('engineering_risk_description').focus();
      swal("Please enter contract description ", 'Fill all fields', "error");
    } else {

      $.get('/add-engineering-schedule', {
          "policy_number": $('#policy_number').val(),
          "engineering_risk_type": $('#engineering_risk_type').val(),
          "engineering_parties": $('#engineering_parties').val(),
          "car_nature_of_business": $('#car_nature_of_business').val(),
          "engineering_risk_description": $('#engineering_risk_description').val(),
          "account_number": $('#customer_number').val(),
          "engineering_risk_number": $('#engineering_risk_number').val(),
          "engineering_item_number": $('#engineering_item_number').val(),
          "currency": $('#policy_currency').val(),
          "engineering_unit": $('#engineering_unit').val(),
          "engineering_si": $('#engineering_si').val(),
          "add_up_engineering": $('#add_up_engineering').prop('checked'),
          "engineering_rate": $('#engineering_rate').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),
          "engineeringkey": $('#engineeringkey').val(),
          "engineering_schedule": $('#engineering_schedule').html(),
          "engineering_beneficiary": $('#engineering_beneficiary').html(),
          "rating_factor": $('#rating_factor').val()
        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Engineering schedule successfully saved!");
              loadEngineeringDetails();
              computenonMotorPremium();

              $('#engineeringkey').val('');
              $('#engineering_si').val('0');
              $('#engineering_rate').val('0');
              $("#btnengineering").html('Click to Add New Item');
              $("#engineering_unit").val('').trigger('change');

            } else {
              toastr.error("Engineering schedule failed to save!");

            }
          });

        }, 'json');
    }

  }




  function computeCoinsuranceShare() {

    var co_si = ($('#reinsurance_rate').val() / 100) * $('#suminsured_2').val();

    $('#reinsurance_SI').val(co_si);


  }

  function addAccidentDetails() {
    tinyMCE.triggerSave();
    if ($('#accident_risk_type').val() == "") {
      document.getElementById('accident_risk_type').focus();
      swal("Please select risk type ", 'Fill all fields', "error");
    } else if ($('#accident_unit').val() == 0) {
      document.getElementById('accident_unit').focus();
      swal("Please select/enter an item  ", 'Fill all fields', "error");
    } else if ($('#accident_si').val() == 0) {
      document.getElementById('accident_si').focus();
      swal("Please enter sum insured ", 'Fill all fields', "error");
    } else if ($('#accident_rate').val() == 0) {
      document.getElementById('accident_rate').focus();
      swal("Please enter accident rate ", 'Fill all fields', "error");
    } else if ($('#accident_risk_description').val() == "") {
      document.getElementById('accident_risk_description').focus();
      swal("Please enter risk description ", 'Fill all fields', "error");
    } else {

      $.get('/add-accident-schedule', {
          "policy_number": $('#policy_number').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),
          "account_number": $('#customer_number').val(),
          "accident_risk_number": $('#accident_risk_number').val(),
          "accident_item_number": $('#accident_item_number').val(),
          "currency": $('#policy_currency').val(),
          "accident_risk_type": $('#accident_risk_type').val(),
          "add_up_accident": $('#add_up_accident').prop('checked'),
          "accident_risk_description": $('#accident_risk_description').val(),
          "accident_item_description": $('#accident_item_description').val(),
          "accident_unit": $('#accident_unit').val(),
          "accident_si": $('#accident_si').val(),
          "accident_rate": $('#accident_rate').val(),
          "accident_sd_rate": $('#accident_sd_rate').val(),
          "accidentkey": $('#accidentkey').val(),
          "accident_schedule": $('#accident_schedule').html(),
          "accident_beneficiary": $('#accident_beneficiary').html(),
          "rating_factor": $('#rating_factor').val(),
          "accident_means_of_conveyance": $('#accident_means_of_conveyance').val()
        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Accident risk successfully added!");
              loadAccidentDetails();
              computenonMotorPremium();

              //$('#accident_risk_type').val(''),
              $('#accident_unit').val(''),
                $('#accident_si').val('0'),
                $('#accident_rate').val('0'),
                $("#btnaccident").html('Click to Add New Item');
              $("#accident_unit").val('').trigger('change');

            } else {
              toastr.error("Risk failed to save!");

            }
          });

        }, 'json');
    }

  }


  function addLiabilityDetails() {
    tinyMCE.triggerSave();
    if ($('#liability_risk_type').val() == "") {
      document.getElementById('liability_risk_type').focus();
      swal("Please select risk type ", 'Fill all fields', "error");
    } else if ($('#liability_unit').val() == 0) {
      document.getElementById('liability_unit').focus();
      swal("Please select/enter an item  ", 'Fill all fields', "error");
    } else if ($('#liability_si').val() == 0) {
      document.getElementById('liability_si').focus();
      swal("Please enter sum insured ", 'Fill all fields', "error");
    } else if ($('#liability_rate').val() == 0 & $('#liability_unit').val() == 'Annual Aggregate Limit') {
      document.getElementById('liability_rate').focus();
      swal("Please enter liability rate ", 'Fill all fields', "error");
    } else if ($('#liability_risk_description').val() == "") {
      document.getElementById('liability_risk_description').focus();
      swal("Please enter risk description ", 'Fill all fields', "error");
    } else {

      $.get('/add-liability-schedule', {
          "policy_number": $('#policy_number').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),
          "account_number": $('#customer_number').val(),
          "liability_risk_number": $('#liability_risk_number').val(),
          "liability_item_number": $('#liability_item_number').val(),
          "currency": $('#policy_currency').val(),
          "liability_risk_type": $('#liability_risk_type').val(),
          "liability_unit": $('#liability_unit').val(),
          "liability_si": $('#liability_si').val(),
          "liability_rate": $('#liability_rate').val(),
          "add_up_liability": $('#add_up_liability').prop('checked'),
          "liability_risk_description": $('#liability_risk_description').val(),
          "liability_item_description": $('#liability_item_description').val(),
          "liability_sd_rate": $('#liability_sd_rate').val(),
          "liabilitykey": $('#liabilitykey').val(),
          "liability_schedule": $('#liability_schedule').html(),
          "liability_beneficiary": $('#liability_beneficiary').html(),
          "rating_factor": $('#rating_factor').val()

        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Liability risk successfully added!");
              loadLiabilityDetails();
              computenonMotorPremium();


              $('#liability_unit').val('');
              $('#liability_si').val('');
              $('#liability_rate').val('');
              $('#liability_schedule').html('');
              $('#liability_beneficiary').html('');
              $("#liabilitybutton").html('Click to Add New Item');

            } else {
              toastr.error("Risk failed to save!");

            }
          });

        }, 'json');
    }

  }


  function disableSameVehicle() {


    $("#vehicle_use").prop("disabled", true);
    $("#preferedcover").prop("disabled", true);
    $("#vehicle_risk").prop("disabled", true);

    $("#commence_date").prop("disabled", true);
    $("#expiry_date").prop("disabled", true);
    $("#policy_product").prop("disabled", true);

    $("#policy_branch").prop("disabled", true);
    $("#policy_sales_type").prop("disabled", true);
    $("#agency").prop("disabled", true);
    $("#policy_currency").prop("disabled", true);

    $('#vehicle_registration_number').val('');
    $('#vehicle_chassis_number').val('');
    $('#vehicle_engine_number').val('');
    $('#vehicle_value').val('0');

    //$("#sticker_number").select2("", "");
    $("#sticker_number").val() = uniq(10);
    $('#certificate_number').val() = uniq(10);
    $('#brown_card_number').val('');
    $('#btn_add_extra').hide();
    //$("#myprevious").trigger("click");

    //$("#myprevious").trigger("click");


  }


  function disableOtherVehicle() {


    $("#vehicle_use").prop("disabled", true);
    $("#preferedcover").prop("disabled", true);
    $("#vehicle_risk").prop("disabled", true);

    $("#commence_date").prop("disabled", true);
    $("#expiry_date").prop("disabled", true);
    $("#policy_product").prop("disabled", true);

    $("#policy_branch").prop("disabled", true);
    $("#policy_sales_type").prop("disabled", true);
    $("#agency").prop("disabled", true);
    $("#policy_currency").prop("disabled", true);




    $('#vehicle_registration_number').val('');
    $('#vehicle_chassis_number').val('');
    $('#vehicle_engine_number').val('');
    $('#vehicle_value').val('0');
    $('#vehicle_cubic_capacity').val('0');


    $("#sticker_number").val('0');
    $("#vehicle_make").val('');
    $("#vehicle_model").val('');
    $("#sticker_number").val() = uniq(10);
    $('#certificate_number').val() = uniq(10);
    $('#brown_card_number').val('');
    $('#btn_add_extra').hide();

    //$("#myprevious").trigger("click");


  }




  function addMotorDetails() {
    if ($('#preferedcover').val() == "") {
      document.getElementById('preferedcover').focus();
      swal("Please select cover ", 'Fill all fields', "error");
    } else if ($('#vehicle_value').val() == "" && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_value').focus();
      swal("Enter vehicle value ", 'Fill all fields', "error");
    } else if ($('#vehicle_buy_back_excess').val() == "" && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_buy_back_excess').focus();
      swal("Please select excess ", 'Fill all fields', "error");
    } else if ($('#vehicle_use').val() == "") {
      document.getElementById('vehicle_use').focus();
      swal("Please select use ", 'Fill all fields', "error");
    } else if ($('#vehicle_risk').val() == "") {
      document.getElementById('vehicle_risk').focus();
      swal("Please select risk ", 'Fill all fields', "error");
    } else if ($('#vehicle_body_type').val() == "") {
      document.getElementById('vehicle_body_type').focus();
      swal("Please select body type ", 'Fill all fields', "error");
    } else if ($('#vehicle_model').val() == "") {
      document.getElementById('vehicle_model').focus();
      swal("Please select model ", 'Fill all fields', "error");
    } else if ($('#vehicle_make').val() == "") {
      document.getElementById('vehicle_make').focus();
      swal("Please select make ", 'Fill all fields', "error");
    } else if ($('#vehicle_registration_number').val() == "") {
      document.getElementById('vehicle_registration_number').focus();
      swal("Please enter registration number ", 'Fill all fields', "error");
    } else if ($('#commence_date').val() == $('#expiry_date').val()) {
      document.getElementById('commence_date').focus();
      sweetAlert("Please ensure the commence date and expiry date is not the same. Check your insurance periods ", 'Fill all fields', "error");
    } else if ($('#agency').val() == "") {
      document.getElementById('agency').focus();
      sweetAlert("Please select an agent ", 'Fill all fields', "error");
    }
    // else if($('#vehicle_chassis_number').val()=="")
    //   {document.getElementById('vehicle_chassis_number').focus(); swal("Please enter chasis number ",'Fill all fields', "error");}
    else if ($('#vehicle_colour').val() == "") {
      document.getElementById('vehicle_colour').focus();
      swal("Please enter vehicle color ", 'Fill all fields', "error");
    } else if ($('#sticker_number').val() == "") {
      document.getElementById('sticker_number').focus();
      swal("Please enter a valid sticker number ", 'Fill all fields', "error");
    } else if ($('#vehicle_seating_capacity').val() == "") {
      document.getElementById('vehicle_seating_capacity').focus();
      swal("Please enter seat number ", 'Fill all fields', "error");
    } else if ($('#vehicle_cubic_capacity').val() == "" & ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_cubic_capacity').focus();
      swal("Please enter cubic capacity ", 'Fill all fields', "error");
    } else if ($('#vehicle_ncd').val() == "") {
      document.getElementById('vehicle_ncd').focus();
      swal("Please select ncd ", 'Fill all fields', "error");
    } else if ($('#vehicle_fleet_discount').val() == "") {
      document.getElementById('vehicle_fleet_discount').focus();
      swal("Please select fleet discount ", 'Fill all fields', "error");
    } else {





      tinyMCE.triggerSave();
      $.get('/add-motor-schedule', {

          "customer_number": $('#customer_number').val(),
          "fullname": $('#fullname').val(),
          "policy_number": $('#policy_number').val(),
          "endorsement_number": $('#endorsement_number').val(),
          "vehicle_registration_number": $('#vehicle_registration_number').val(),
          "policy_product": $('#policy_product').val(),
          "transaction_date": $('#transaction_date').val(),
          "acceptance_date": $('#acceptance_date').val(),
          "issue_date": $('#issue_date').val(),
          "policy_sales_type": $('#policy_sales_type').val(),
          "policy_sales_channel": $('#policy_sales_channel').val(),
          "policy_currency": $('#policy_currency').val(),
          "policy_status": $('#policy_status').val(),
          "transaction_type": $('#transaction_type').val(),
          "policy_branch": $('#policy_branch').val(),
          "policy_workgroup": $('#policy_workgroup').val(),
          "agency": $('#agency').val(),
          "preferedcover": $('#preferedcover').val(),
          "policy_clause": $('#policy_clause').val(),
          "policy_interest": $('#policy_interest').val(),
          "policy_upper_text": $('#policy_upper_text').val(),
          "policy_lower_text": $('#policy_lower_text').val(),
          "policy_end_text": $('#policy_end_text').val(),
          "policy_renewal_text": $('#policy_renewal_text').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),
          "account_manager": $('#account_manager').val(),
          "master_policy_number": $('#master_policy_number').val(),
          "rating_factor": $('#rating_factor').val(),



          "vehicle_value": $('#vehicle_value').val(),
          "vehicle_buy_back_excess": $('#vehicle_buy_back_excess').val(),
          "voluntary_excess": $('#voluntary_excess').val(),
          "vehicle_tppdl_standard": $('#vehicle_tppdl_standard').val(),
          "vehicle_tppdl_value": $('#vehicle_tppdl_value').val(),
          "vehicle_pa_value": $('#vehicle_pa_value').val(),
          "vehicle_body_type": $('#vehicle_body_type').val(),
          "vehicle_model": $('#vehicle_model').val(),
          "vehicle_make": $('#vehicle_make').val(),
          "vehicle_use": $('#vehicle_use').val(),
          "vehicle_make_year": $('#vehicle_make_year').val(),
          "vehicle_cubic_capacity": $('#vehicle_cubic_capacity').val(),
          "vehicle_seating_capacity": $('#vehicle_seating_capacity').val(),
          "vehicle_registration_number": $('#vehicle_registration_number').val(),
          "vehicle_chassis_number": $('#vehicle_chassis_number').val(),
          "vehicle_engine_number": $('#vehicle_engine_number').val(),
          "vehicle_interest_status": $('#vehicle_interest_status').val(),
          "vehicle_interest_name": $('#vehicle_interest_name').val(),
          "vehicle_risk": $('#vehicle_risk').val(),
          "vehicle_ncd": $('#vehicle_ncd').val(),
          "vehicle_fleet_discount": $('#vehicle_fleet_discount').val(),
          "vehicle_owner_name": $('#vehicle_owner_name').val(),

          "charge_type": $('#charge_type').val(),
          "motor_documentation": $('#motor_documentation').val(),





          "gross_premium": $('#gross_premium').val(),
          "vehicle_colour": $('#vehicle_colour').val(),
          "vehicle_register_date": $('#vehicle_register_date').val(),
          "vehicle_tonnage_capacity": $('#vehicle_tonnage_capacity').val(),
          "vehicle_mileage_number": $('#vehicle_mileage_number').val(),
          "vehicle_trailer_number": $('#vehicle_trailer_number').val(),


          "vehicle_log_book": $('#vehicle_log_book').val(),
          "vehicle_model_description": $('#vehicle_model_description').val(),
          "vehicle_purchase_price": $('#vehicle_purchase_price').val(),

          "vehicle_lta_upload": $('#vehicle_lta_upload').val(),
          "vehicle_lta_transmission": $('#vehicle_lta_transmission').val(),

          "sticker_number": $('#sticker_number').val(),
          "certificate_number": $('#certificate_number').val(),
          "brown_card_number": $('#brown_card_number').val(),


          "execessbought": $('#execessbought').val(),
          "excess_charge_rate": $('#excess_charge_rate').val(),


          "tpbasic": $('#tpbasic').val(),
          "owndamage": $('#owndamage').val(),
          "ccage": $('#ccage').val(),
          //Edits
          "tpbasic_edit": $('#tpbasic_edit').val(),
          "owndamage_edit": $('#owndamage_edit').val(),
          "ccage_edit": $('#ccage_edit').val(),

          //Endedits
          "officepremium": $('#officepremium').val(),
          "ncd": $('#ncd').val(),
          "fleet": $('#fleet').val(),
          "loading": $('#loading').val(),
          "contribution": $('#contribution').val(),
          "commission_rate": $('#commission_rate_motor').val(),
          "discount_rate": $('#motor_discount').val(),
          "commission_motor_2": $('#commission_motor_2').val(),
          "commission_motor_3": $('#commission_motor_3').val(),
          "commission_motor_4": $('#commission_motor_4').val(),

          "reinsurance_SI": $('#reinsurance_SI').val(),
          "reinsurance_rate": $('#reinsurance_rate').val(),
          "premium_due_motor": $('#premium_due_motor').val(),
          "discount_rate": $('#motor_discount').val(),
          "netpremium": $('#premium_due_motor_final').val(),
          "collision_value": $('#collision_value').val()



        },
        function(data) {

          $.each(data, function(key, value) {
            if (data.OK == "OK") {

              swal($('#vehicle_registration_number').val() + " was successfully added.", ".");

              $('#masterform input[name="ReferenceNumber"]').val(data.ReferenceNumber);

              $('#btn_view_schedule').show();
              $('#btn_add_extra').show();
              $('#master_save').hide();
              hideSaveButton();
            } else {

              swal($('#vehicle_registration_number').val() + " could save at this time.", ":(");


            }

          });

        }, 'json');



    }

  }


  function askToSave() {
    swal({
        title: "Are you sure?",
        text: "Do you want save this vehicle details?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#239B56",
        cancelButtonText: "No, cancel plx!",
        confirmButtonText: "Yes, save it!",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {

          addMotorDetails();


        } else {
          swal("Cancelled", "Process cancelled.", "error");
        }
      });


  }

  function printVehicleSchedule() {

    var motor_reference = $('#ReferenceNumber').val();
    window.location = "/print-schedule/" + motor_reference;
  }

  function sameVehicleAsk() {


    swal({
        title: 'If you are sure you want to add new vehicle',
        text: "Are the vehicle details same as previous!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, it is same!',
        cancelButtonText: "No, different !"
      },
      function(isConfirm) {
        if (isConfirm) {
          showSaveButton();
          swal("Add Another Vehicle.", "Please scroll upward to add next vehicle!");
          disableSameVehicle();
          //$('#btn_view_schedule').show();
          $('#btn_add_extra').hide();
          //$("#previous").click()
          // $('a .previous').click();

          //<a href="#previous" role="menuitem">Previous</a>
        } else {
          //swal($('#vehicle_registration_number').val() +" was successfully added.","Please go to previous page to add the next vehicle!");
          showSaveButton();
          swal("Add Another Vehicle.", "Please scroll upward to add next vehicle!");
          disableOtherVehicle();
          $('#btn_add_extra').hide();

          //$("#previous").click();
        }
      });

  }


  function getExcessStatus() {
    if ($('#vehicle_buy_back_excess').val() == "Voluntary Excess Applicable") {
      $('#voluntary_excess').prop('readonly', false);
      $('#default_excess').prop('readonly', true);
    } else if (($('#vehicle_buy_back_excess').val() == "Excess Is Not Applicable" || $('#vehicle_buy_back_excess').val() == "No")) {
      $('#voluntary_excess').val(0);
      $('#voluntary_excess').prop('readonly', true);
      $('#default_excess').prop('readonly', true);
    } else if (($('#vehicle_buy_back_excess').val() == "Excess Is Bought" || $('#vehicle_buy_back_excess').val() == "Yes" || $('#vehicle_buy_back_excess').val() == "Excess Is Applicable")) {
      $('#voluntary_excess').val(0);
      $('#voluntary_excess').prop('readonly', true);
      $('#default_excess').prop('readonly', false);
    }
  }


  function addMotorDetailsSilent() {
    if ($('#preferedcover').val() == "") {
      document.getElementById('preferedcover').focus();
      swal("Please select cover ", 'Fill all fields', "error");
    } else if ($('#vehicle_value').val() == "" & ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_value').focus();
      swal("Enter vehicle value ", 'Fill all fields', "error");
    } else if ($('#vehicle_buy_back_excess').val() == "" & ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_buy_back_excess').focus();
      swal("Please select excess ", 'Fill all fields', "error");
    } else if ($('#vehicle_use').val() == "") {
      document.getElementById('vehicle_use').focus();
      swal("Please select use ", 'Fill all fields', "error");
    } else if ($('#vehicle_risk').val() == "") {
      document.getElementById('vehicle_risk').focus();
      swal("Please select risk ", 'Fill all fields', "error");
    } else if ($('#vehicle_body_type').val() == "") {
      document.getElementById('vehicle_body_type').focus();
      swal("Please select body type ", 'Fill all fields', "error");
    } else if ($('#vehicle_model').val() == "") {
      document.getElementById('vehicle_model').focus();
      swal("Please select model ", 'Fill all fields', "error");
    } else if ($('#vehicle_make').val() == "") {
      document.getElementById('vehicle_make').focus();
      swal("Please select make ", 'Fill all fields', "error");
    } else if ($('#vehicle_registration_number').val() == "") {
      document.getElementById('vehicle_registration_number').focus();
      swal("Please enter registration number ", 'Fill all fields', "error");
    } else if ($('#vehicle_chassis_number').val() == "" & $('#preferedcover').val() != "Third party") {
      document.getElementById('vehicle_chassis_number').focus();
      swal("Please enter chasis number ", 'Fill all fields', "error");
    } else if ($('#sticker_number').val() == "") {
      document.getElementById('sticker_number').focus();
      swal("Please enter a valid sticker number ", 'Fill all fields', "error");
    } else if ($('#vehicle_seating_capacity').val() == "") {
      document.getElementById('vehicle_seating_capacity').focus();
      swal("Please enter seat number ", 'Fill all fields', "error");
    } else if ($('#vehicle_cubic_capacity').val() == "" & ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_cubic_capacity').focus();
      swal("Please enter cubic capacity ", 'Fill all fields', "error");
    } else if ($('#vehicle_ncd').val() == "") {
      document.getElementById('vehicle_ncd').focus();
      swal("Please select ncd ", 'Fill all fields', "error");
    } else if ($('#vehicle_fleet_discount').val() == "") {
      document.getElementById('vehicle_fleet_discount').focus();
      swal("Please select fleet discount ", 'Fill all fields', "error");
    } else if ($('#premium_due_motor').val() == "") {
      document.getElementById('premium_due_motor').focus();
      swal("Please ensure premium has been computed first before saving ", 'Fill all fields', "error");
    } else {

      tinyMCE.triggerSave();

      $.get('/add-motor-schedule', {

          "customer_number": $('#customer_number').val(),
          "fullname": $('#fullname').val(),
          "policy_number": $('#policy_number').val(),
          "endorsement_number": $('#endorsement_number').val(),
          "vehicle_registration_number": $('#vehicle_registration_number').val(),
          "policy_product": $('#policy_product').val(),
          "transaction_date": $('#transaction_date').val(),
          "acceptance_date": $('#acceptance_date').val(),
          "issue_date": $('#issue_date').val(),
          "policy_sales_type": $('#policy_sales_type').val(),
          "policy_sales_channel": $('#policy_sales_channel').val(),
          "policy_currency": $('#policy_currency').val(),
          "policy_status": $('#policy_status').val(),
          "transaction_type": $('#transaction_type').val(),
          "policy_branch": $('#policy_branch').val(),
          "policy_workgroup": $('#policy_workgroup').val(),
          "agency": $('#agency').val(),
          "preferedcover": $('#preferedcover').val(),
          "policy_clause": $('#policy_clause').val(),
          "policy_interest": $('#policy_interest').val(),
          "policy_upper_text": $('#policy_upper_text').val(),
          "policy_lower_text": $('#policy_lower_text').val(),
          "policy_end_text": $('#policy_end_text').val(),
          "policy_renewal_text": $('#policy_renewal_text').val(),
          "policy_endorsement_text": $('#policy_endorsement_text').html(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "issue_date": $('#issue_date').val(),
          "account_manager": $('#account_manager').val(),
          "master_policy_number": $('#master_policy_number').val(),
          "motorkey": $('#motorkey').val(),
          "vehiclekey": $('#vehiclekey').val(),
          "endorsement_count": $('#endorsement_count').val(),
          "renewal_count": $('#renewal_count').val(),
          "endorsement_status": $('#endorsement_status').val(),
          "rating_factor": $('#rating_factor').val(),



          "vehicle_value": $('#vehicle_value').val(),
          "vehicle_buy_back_excess": $('#vehicle_buy_back_excess').val(),
          "vehicle_tppdl_standard": $('#vehicle_tppdl_standard').val(),
          "vehicle_tppdl_value": $('#vehicle_tppdl_value').val(),
          "vehicle_body_type": $('#vehicle_body_type').val(),
          "vehicle_model": $('#vehicle_model').val(),
          "vehicle_make": $('#vehicle_make').val(),
          "vehicle_use": $('#vehicle_use').val(),
          "vehicle_make_year": $('#vehicle_make_year').val(),
          "vehicle_cubic_capacity": $('#vehicle_cubic_capacity').val(),
          "vehicle_seating_capacity": $('#vehicle_seating_capacity').val(),
          "vehicle_registration_number": $('#vehicle_registration_number').val(),
          "vehicle_chassis_number": $('#vehicle_chassis_number').val(),
          "vehicle_engine_number": $('#vehicle_engine_number').val(),
          "vehicle_interest_status": $('#vehicle_interest_status').val(),
          "vehicle_interest_name": $('#vehicle_interest_name').val(),
          "vehicle_risk": $('#vehicle_risk').val(),
          "vehicle_ncd": $('#vehicle_ncd').val(),
          "vehicle_fleet_discount": $('#vehicle_fleet_discount').val(),
          "vehicle_owner_name": $('#vehicle_owner_name').val(),
          "gross_premium": $('#gross_premium').val(),
          "vehicle_colour": $('#vehicle_colour').val(),
          "vehicle_register_date": $('#vehicle_register_date').val(),
          "vehicle_tonnage_capacity": $('#vehicle_tonnage_capacity').val(),
          "vehicle_mileage_number": $('#vehicle_mileage_number').val(),
          "vehicle_trailer_number": $('#vehicle_trailer_number').val(),
          "vehicle_log_book": $('#vehicle_log_book').val(),
          "vehicle_model_description": $('#vehicle_model_description').val(),
          "vehicle_purchase_price": $('#vehicle_purchase_price').val(),
          "vehicle_lta_upload": $('#vehicle_lta_upload').val(),
          "vehicle_lta_transmission": $('#vehicle_lta_transmission').val(),

          "sticker_number": $('#sticker_number').val(),
          "certificate_number": $('#certificate_number').val(),
          "brown_card_number": $('#brown_card_number').val(),


          "execessbought": $('#execessbought').val(),
          "excess_charge_rate": $('#excess_charge_rate').val(),

          "charge_type": $('#charge_type').val(),
          "motor_documentation": $('#motor_documentation').val(),


          "tpbasic": $('#tpbasic').val(),
          "owndamage": $('#owndamage').val(),
          "ccage": $('#ccage').val(),
          //Edits
          "tpbasic_edit": $('#tpbasic_edit').val(),
          "owndamage_edit": $('#owndamage_edit').val(),
          "ccage_edit": $('#ccage_edit').val(),

          //Endedits
          "officepremium": $('#officepremium').val(),
          "ncd": $('#ncd').val(),
          "fleet": $('#fleet').val(),
          "loading": $('#loading').val(),
          "contribution": $('#contribution').val(),
          "commission_rate": $('#commission_rate_motor').val(),

          "commission_motor_2": $('#commission_motor_2').val(),
          "commission_motor_3": $('#commission_motor_3').val(),
          "commission_motor_4": $('#commission_motor_4').val(),

          "reinsurance_SI": $('#reinsurance_SI').val(),
          "reinsurance_rate": $('#reinsurance_rate').val(),
          "discount_rate": $('#motor_discount').val(),
          "premium_due_motor": $('#premium_due_motor').val(),
          "netpremium": $('#premium_due_motor_final').val()



        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {

              $('#vehicle_registration_number').val('');
              toastr.success("Motor schedule successfully saved!");
              loadMotorSchedule();

              window.location = "/print-schedule/" + data["ReferenceNumber"];


            } else {
              toastr.error("Motor schedule failed to save!");

            }
          });

        }, 'json');
    }

  }


  function saveNonMotorPolicy() {

    //alert($('#myrisk').val());
    tinyMCE.triggerSave();

    if ($('#premium_due_non_motor_final').val() != 0 && $('#suminsured_2').val() != 0 || $('#policy_product').val() == "Travel Insurance") {

      $.get('/add-non-motor-policy', {



          "customer_number": $('#customer_number').val(),
          "fullname": $('#fullname').val(),
          "policy_number": $('#policy_number').val(),
          "endorsement_number": $('#endorsement_number').val(),
          "vehicle_registration_number": $('#vehicle_registration_number').val(),
          "policy_product": $('#policy_product').val(),
          "transaction_date": $('#transaction_date').val(),
          "issue_date": $('#issue_date').val(),
          "acceptance_date": $('#acceptance_date').val(),
          "policy_sales_type": $('#policy_sales_type').val(),
          "policy_sales_channel": $('#policy_sales_channel').val(),
          "policy_currency": $('#policy_currency').val(),
          "policy_status": $('#policy_status').val(),
          "transaction_type": $('#transaction_type').val(),
          "policy_branch": $('#policy_branch').val(),
          "policy_workgroup": $('#policy_workgroup').val(),
          "agency": $('#agency').val(),
          "preferedcover": $('#myrisk').val(),
          "myriskclass": $('#myriskclass').val(),
          "policy_clause": $('#policy_clause').val(),
          "policy_interest": $('#policy_interest').val(),
          "policy_upper_text": $('#policy_upper_text').val(),
          "policy_lower_text": $('#policy_lower_text').val(),
          "policy_end_text": $('#policy_end_text').val(),
          "policy_renewal_text": $('#policy_renewal_text').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "period_days": $('#period_days').val(),
          "gross_premium": $('#premium_due_non_motor_final').val(),
          "account_manager": $('#account_manager').val(),
          "discount_rate": $('#non_motor_discount').val(),
          "commission_rate": $('#commission_rate_non_motor').val(),
          "commission_non_motor_2": $('#commission_non_motor_2').val(),
          "commission_non_motor_3": $('#commission_non_motor_3').val(),
          "commission_non_motor_4": $('#commission_non_motor_4').val(),
          "master_policy_number": $('#master_policy_number').val(),
          "reinsurance_SI": $('#reinsurance_SI').val(),
          "reinsurance_rate": $('#reinsurance_rate').val(),
          "sum_insured": $('#suminsured_2').val(),
          "non_motor_sticker": $('#non_motor_sticker').val(),
          "rating_factor": $('#rating_factor').val(),
          "non_motor_levy": $('#non_motor_levy').val(),
          //travel insurance details 
          "passport_number": $('#travel_passport_number').val(),
          "existing_medical_condition": $('#existing_medical_condition').val(),
          "medical_advice": $('#medical_advice').val(),
          "medical_supervision": $('#medical_supervision').val(),
          "travel_product_name": $('#travel_product_name').val(),
          "travel_product_plan": $('#travel_product_plan').val(),
          "travel_country_of_destination": $('#travel_country_of_dest').val(),
          "policy_minor_holder_status": $('#policy_minor_holder_status').is(":checked"),

        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {

              toastr.success("Policy successfully saved!");
              window.location = "/print-schedule/" + data["ReferenceNumber"];


            } else {
              toastr.error("Policy failed to save!");
            }
          });

        }, 'json');
    } else {
      swal("Please ensure risk has been added!");
    }

  }





  function loadPerilsApplied() {


    $.get('/get-fire-peril-applied', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#firePerilTable tbody').empty();
        $.each(data, function(key, value) {
          $('#firePerilTable tbody').append('<tr><td>' + value['fire_peril'] + '</td><td>' + value['peril_rate'] + '</td><td>' + value['created_on'] + '</td><td>' + value['created_by'] + '</td><td><a a href="#"><i onclick="removePeril(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }

  function getExchangeRate() {




    var mycurrency = $('#policy_currency').val();

    //alert(mycurrency);

    $.get('/get-exchange-rate-value', {
        "policy_currency": mycurrency,
      },
      function(data) {

        $.each(data, function(key, value) {



          $('#applied_exchange').val(data.exchange_rate);
          //alert(data.exchange_rate);


        });

      }, 'json');

  }

  function loadMotorSchedule() {


    $.get('/get-motor-schedule', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#motorScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#motorScheduleTable tbody').append('<tr><td>' + value['vehicle_registration_number'] + '</td><td>' + value['vehicle_cover'] + '</td><td>' + value['vehicle_value'] + '</td><td>' + value['vehicle_tppdl_value'] + '</td><td>' + value['gross_premium'] + '</td><td>' + value['premium_due'] + '</td><td><a a href="#"><i onclick="removeMotor(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }


  function loadMarineDetails() {


    $.get('/get-marine-schedule', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#marineScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#marineScheduleTable tbody').append('<tr><td>' + value['marine_vessel'] + '</td><td>' + value['marine_voyage'] + '</td><td>' + value['marine_means_of_conveyance'] + '</td><td>' + thousands_separators(value['marine_sum_insured']) + '</td><td>' + value['marine_rate'] + '</td><td>' + thousands_separators(value['premium_due']) + '</td><td>' + value['created_on'] + '</td><td>' + value['created_by'] + '</td><td><a a href="#"><i onclick="editMarine(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeMarine(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }


  function loadBondDetails() {


    $.get('/get-bond-schedule', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#bondScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#bondScheduleTable tbody').append('<tr><td>' + value['bond_risk_type'] + '</td><td>' + value['bond_interest'] + '</td><td>' + value['bond_contract_description'] + '</td><td>' + thousands_separators(value['bond_sum_insured']) + '</td><td>' + value['bond_rate'] + '</td><td>' + thousands_separators(value['premium_due']) + '</td><td>' + value['created_on'] + '</td><td>' + value['created_by'] + '</td><td><a a href="#"><i onclick="editBond(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeBond(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }



  function loadEngineeringDetails() {


    $.get('/get-engineering-schedule', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#engineeringScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#engineeringScheduleTable tbody').append('<tr><td>' + value['risk_number'] + '</td><td>' + value['item_number'] + '</td><td>' + value['risk_description'] + '</td><td>' + value['unit'] + '</td><td>' + thousands_separators(value['sum_insured']) + '</td><td>' + value['rate'] + '</td><td>' + value['net_premium'] + '</td><td>' + value['created_by'] + '</td><td>' + value['created_on'] + '</td><td><a a href="#"><i onclick="editEngineering(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeEngineering(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }


  function loadAccidentDetails() {


    $.get('/get-accident-schedule', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#accidentScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#accidentScheduleTable tbody').append('<tr><td>' + value['risk_number'] + '</td><td>' + value['item_number'] + '</td><td>' + value['risk_description'] + '</td><td>' + value['unit'] + '</td><td>' + thousands_separators(value['sum_insured']) + '</td><td>' + value['rate'] + '</td><td>' + value['net_premium'] + '</td><td>' + value['created_by'] + '</td><td>' + value['created_on'] + '</td><td><a a href="#"><i onclick="editAccident(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeAccident(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }



  function loadLiabilityDetails() {


    $.get('/get-liability-schedule', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#liabilityScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#liabilityScheduleTable tbody').append('<tr><td>' + value['risk_number'] + '</td><td>' + value['item_number'] + '</td><td>' + value['risk_description'] + '</td><td>' + value['unit'] + '</td><td>' + thousands_separators(value['sum_insured']) + '</td><td>' + value['rate'] + '</td><td>' + value['net_premium'] + '</td><td>' + value['created_by'] + '</td><td>' + value['created_on'] + '</td><td><a a href="#"><i onclick="editLiability(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeLiability(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }



  function loadProperties() {


    $.get('/get-fire-property', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {


        $('#fireRiskTable tbody').empty();
        $.each(data, function(key, value) {
          $('#fireRiskTable tbody').append('<tr><td>' + value['risk_number'] + '</td><td>' + value['item_number'] + '</td><td>' + value['property_description'] + '</td><td>' + thousands_separators(value['item_value']) + '</td><td>' + value['rate'] + '</td><td>' + thousands_separators(value['actual_premium']) + '</td><td><a a href="#"><i onclick="editProperty(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeProperty(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }


  function loadPropertyItem() {


    $.get('/get-fire-property-item', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#fireItemTable tbody').empty();
        $.each(data, function(key, value) {
          $('#fireItemTable tbody').append('<tr><td>' + value['property_number'] + '</td><td>' + value['item_number'] + '</td><td>' + value['item_description'] + '</td><td>' + value['created_on'] + '</td><td>' + value['created_by'] + '</td><td><a a href="#"><i onclick="removePropertyItem(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }








  function removeProperty(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-fire-property', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadProperties();
                computenonMotorPremium();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }



  function editProperty(id) {

    $.get("/edit-fire-property", {
        "id": id
      },
      function(json) {

        $('#masterform input[name="firekey"]').val(json.firekey);

        $('#masterform select[name="fire_risk_covered"]').val(json.fire_risk_covered).select2();
        $('#masterform select[name="property_type"]').val(json.property_type).select2();
        $('#masterform input[name="property_number_item"]').val(json.property_number);
        $('#masterform input[name="property_item_number"]').val(json.item_number);
        $('#masterform input[name="item_value"]').val(json.item_value);
        $('#masterform input[name="unit_number"]').val(json.unit_number);
        $('#masterform textarea[name="property_address"]').val(json.property_address);
        $('#masterform input[name="fire_rate"]').val(json.fire_rate);
        $('#masterform input[name="lta"]').val(json.lta);
        $('#masterform input[name="fire_extinguisher"]').val(json.fire_extinguisher);
        $('#masterform input[name="fire_hydrant"]').val(json.fire_hydrant);
        $('#masterform input[name="staff_discount"]').val(json.staff_discount);
        $('#masterform input[name="collapserate"]').val(json.collapserate);
        $(tinymce.get('property_description').getBody()).html(json.property_description);

        $('#masterform textarea[name="property_description"]').val(json.property_description);
        $('#masterform button[name="btnfire"]').html('Click to Update Item');
        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }


  function editEngineering(id) {

    $.get("/edit-engineering-detail", {
        "id": id
      },
      function(json) {

        $('#masterform input[name="engineeringkey"]').val(json.engineeringkey);

        $('#masterform select[name="engineering_risk_type"]').val(json.engineering_risk_type).select2();
        $('#masterform select[name="engineering_unit"]').val(json.engineering_unit).select2();
        $('#masterform input[name="engineering_risk_number"]').val(json.engineering_risk_number);
        $('#masterform input[name="engineering_si"]').val(json.engineering_si);
        $('#masterform input[name="engineering_item_number"]').val(json.engineering_item_number);
        $(tinymce.get('engineering_risk_description').getBody()).html(json.engineering_risk_description);
        $('#masterform textarea[name="engineering_risk_description"]').val(json.engineering_risk_description);
        $('#masterform input[name="engineering_rate"]').val(json.engineering_rate);
        $('#masterform input[name="engineering_parties"]').val(json.engineering_parties);
        $('#masterform input[name="car_nature_of_business"]').val(json.car_nature_of_business);
        $('#masterform div[name="engineering_schedule"]').html(json.engineering_schedule);
        $('#masterform div[name="engineering_beneficiary"]').html(json.engineering_beneficiary);
        $('#masterform a[name="btnengineering"]').html('Click to Update Item');

        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }



  function editAccident(id) {

    $.get("/edit-accident-detail", {
        "id": id
      },
      function(json) {

        $('#masterform input[name="accidentkey"]').val(json.accidentkey);

        $('#masterform select[name="accident_risk_type"]').val(json.accident_risk_type).select2();
        $('#masterform select[name="accident_unit"]').val(json.accident_unit).select2();
        $('#masterform input[name="accident_risk_number"]').val(json.accident_risk_number);
        $('#masterform input[name="accident_si"]').val(json.accident_si);
        $('#masterform input[name="accident_item_number"]').val(json.accident_item_number);

        $(tinymce.get('accident_risk_description').getBody()).html(json.accident_risk_description);
        $('#masterform input[name="accident_risk_description"]').val(json.accident_risk_description);
        $('#masterform input[name="accident_rate"]').val(json.accident_rate);
        $('#masterform div[name="accident_schedule"]').html(json.accident_schedule);
        $('#masterform div[name="accident_beneficiary"]').html(json.accident_beneficiary);
        $('#masterform a[name="btnaccident"]').html('Click to Update Item');

        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }




  function editLiability(id) {

    $.get("/edit-liability-detail", {
        "id": id
      },
      function(json) {

        $('#masterform input[name="liabilitykey"]').val(json.liabilitykey);

        $('#masterform select[name="liability_risk_type"]').val(json.liability_risk_type).select2();
        $('#masterform select[name="liability_unit"]').val(json.liability_unit).select2();
        $('#masterform input[name="liability_risk_number"]').val(json.liability_risk_number);
        $('#masterform input[name="liability_si"]').val(json.liability_si);
        $('#masterform input[name="liability_item_number"]').val(json.liability_item_number);
        $(tinymce.get('liability_risk_description').getBody()).html(json.liability_risk_description);

        $('#masterform input[name="liability_risk_description"]').val(json.liability_risk_description);
        $('#masterform input[name="liability_rate"]').val(json.liability_rate);
        $('#masterform div[name="liability_schedule"]').html(json.liability_schedule);
        $('#masterform div[name="liability_beneficiary"]').html(json.liability_beneficiary);
        $('#masterform a[name="liabilitybutton"]').html('Click to Update Item');

        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }


  function editBond(id) {

    $.get("/edit-bond-detail", {
        "id": id
      },
      function(json) {

        $('#masterform input[name="bondkey"]').val(json.bondkey);
        $('#masterform select[name="bond_risk_type"]').val(json.bond_risk_type).select2();
        $('#masterform select[name="bond_risk_type_class"]').val(json.bond_risk_type_class).select2();
        $('#masterform input[name="bond_interest"]').val(json.bond_interest);
        $('#masterform input[name="bond_interest_address"]').val(json.bond_interest_address);
        $('#masterform input[name="contract_sum"]').val(json.contract_sum);
        $('#masterform textarea[name="bond_contract_description"]').val(json.bond_contract_description);
        $('#masterform input[name="bond_sum_insured"]').val(json.bond_sum_insured);
        $('#masterform input[name="bond_rate"]').val(json.bond_rate);

        $('#masterform input[name="bond_declaration_number"]').val(json.declaration_number);
        $('#masterform input[name="bond_serial_number"]').val(json.serial_number);
        $('#masterform input[name="bond_exchange_rate"]').val(json.exchange_rate);
        $('#masterform input[name="valid_until"]').val(json.valid_until);
        $('#masterform select[name="bond_template"]').val(json.template).select2();

        $('#masterform button[name="btnbond"]').html('Click to Update Item');


        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }


  function editMarine(id) {

    $.get("/edit-marine-detail", {
        "id": id
      },
      function(json) {

        $('#masterform input[name="marinekey"]').val(json.marinekey);
        $('#masterform select[name="marine_risk_type"]').val(json.marine_risk_type).select2();
        $('#masterform input[name="marine_sum_insured"]').val(json.marine_sum_insured);
        $('#masterform input[name="marine_rate"]').val(json.marine_rate);
        $('#masterform input[name="marine_interest"]').val(json.marine_interest);
        $('#masterform input[name="marine_insurance_condition"]').val(json.marine_insurance_condition);
        $('#masterform input[name="marine_vessel"]').val(json.marine_vessel);
        $('#masterform input[name="marine_voyage"]').val(json.marine_voyage);
        $('#masterform input[name="marine_valuation"]').val(json.marine_valuation);
        $('#masterform textarea[name="marine_means_of_conveyance"]').val(json.marine_means_of_conveyance);
        $('#masterform textarea[name="marine_condition"]').val(json.marine_condition);
        $('#masterform button[name="btnmarine"]').html('Click to Update Item');


        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }




  function removePropertyItem(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-fire-property-item', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadPropertyItem();
                computenonMotorPremium();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }




  function removeMarine(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-marine-schedule', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadMarineDetails();
                computenonMotorPremium();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }



  function removeMotor(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-motor-schedule', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadMotorSchedule();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }





  function removeAccident(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-accident-schedule', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadAccidentDetails();
                computenonMotorPremium();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }


  function removeLiability(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-liability-schedule', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadLiabilityDetails();
                computenonMotorPremium();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }


  function removeEngineering(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-engineering-schedule', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadEngineeringDetails();
                computenonMotorPremium();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }


  function removeBond(id) {

    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-bond-schedule', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadBondDetails();
                computenonMotorPremium();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');




      });
  }












  function removePeril(id) {

    $.get('/delete-fire-peril', {
        "ID": id
      },
      function(data) {

        $.each(data, function(key, value) {
          if (value == "OK") {
            loadPerilsApplied();
          } else {
            swal("Cancelled", "Failed to be removed from list.", "error");

          }

        });

      }, 'json');

  }



  function loadCustomer() {


    $.get('/load-customer-details', {
        "search": $('#search').val()
      },
      function(data) {

        $('#searchTable tbody').empty();
        $.each(data, function(key, value) {
          $('#searchTable tbody').append('<tr><td><a a href="#" class="text-info" onclick="setCustomer(\'' + value['ID'] + '\')">' + value['NAME'] + '</a></td><td>' + value['ID'] + '</td><td>' + value['CREATED BY'] + '</td></tr>');
        });

      }, 'json');
  }


  function setCustomer(id) {

    $.get("/get-customer",

      {
        "id": id
      },
      function(json) {

        //swal(json.account_number);

        $('#customer_number').val(json.fullname);
        $('#get-customer-form').modal('toggle')


      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }

  function loadAgent() {
    //alert($('#agentsearch').val());

    $.get('/load-agent-details', {

        "search": $('#agentsearch').val()
      },
      function(data) {

        $('#agentTable tbody').empty();
        $.each(data, function(key, value) {
          $('#agentTable tbody').append('<tr><td><a a href="#" class="text-info" onclick="setAgent(\'' + value['id'] + '\')">' + value['agent_name'] + '</a></td><td>' + value['agent_code'] + '</td><td>' + value['type'] + '</td></tr>');
        });

      }, 'json');
  }



  function getEngineeringText(endorsementflag) {

    var myendtxt = endorsementflag.value;

    //alert(myendtxt);
    $.get('/get-engineering-text', {
        "engineeringflag": myendtxt,
      },
      function(data) {

        $.each(data, function(key, value) {

          $('#masterform div[name="engineering_beneficiary"]').html(data.engineeringtext);


        });

      }, 'json');

  }



  function setAgent(id) {

    $.get("/get-agent",

      {
        "id": id
      },
      function(json) {

        swal(json.agent_code);
        $('#agent_number').val(json.agent_name);


      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });

  }
</script>




<script src="{{ asset('/event_components/jquery.min.js')}}"></script>


<script type="text/javascript">
  $(function() {
    $('#insurance_period').daterangepicker({
      "minDate": moment('2015-06-14 0'),
      "startDate": moment(),
      "endDate": moment().add(1, 'years').subtract(1, 'days'),
      "showDropdowns": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>


<script type="text/javascript">
  $(function() {
    $('#transaction_date').daterangepicker({
      "maxDate": moment(),
      "singleDatePicker": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  var date = "{{ Carbon\Carbon::now()->format('Y-m-d '); }}";

  $(function() {
    $('#commence_date').daterangepicker({
      // "minDate": moment().subtract(1, 'weeks'),
      "minDate": moment(date),
      //"minDate": moment(),
      "maxDate": moment().add(1, 'years'),
      "singleDatePicker": true,
      "showDropdowns": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#expiry_date').daterangepicker({
      "minDate": moment('2015-01-01 '),
      "startDate": moment().add(1, 'years').subtract(1, 'days'),
      "singleDatePicker": true,
      "showDropdowns": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>


<script type="text/javascript">
  $(function() {
    $('#issue_date').daterangepicker({
      "minDate": moment('2015-06-14 0'),
      "maxDate": moment(),
      "singleDatePicker": true,
      "showDropdowns": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#acceptance_date').daterangepicker({
      "minDate": moment('2015-06-14 0'),
      "singleDatePicker": true,
      "showDropdowns": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#survey_date').daterangepicker({
      "minDate": moment('2015-06-14 0'),
      "maxDate": moment(),
      "singleDatePicker": true,
      "showDropdowns": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>








<script>
  function getProductPlan() {
    $.get('/get-travel-product-plan', {
        "travel_product_name": $('#travel_product_name').val(),
      },
      function(data) {

        $('#travel_product_plan').empty();
        $.each(data, function(key, value) {
          $('#travel_product_plan').append($('<option></option>').val(this['plan_name']).html(this['plan_name']));
        });

        getCountryDestList()

      }, 'json');
  }

  function getCountryDestList() {
    if ($('#travel_product_name').val() != '' && $('#travel_product_plan').val() != '') {
      $.get('/get-country-destination-list', {
          "travel_product_plan": $('#travel_product_plan').val(),
        },
        function(data) {

          $('#travel_country_of_dest').empty();
          $.each(data, function(key, value) {
            $('#travel_country_of_dest').append($('<option></option>').val(this['name']).html(this['name']));
          });

        }, 'json');
    }

  }


  function setPeriodDays() {
    if ($('#rating_factor').val() == "Pro Rata") {
      $('#period_days_div').show();
    } else {
      $('#period_days_div').hide();
    }
  }

  function calculateShortPeriodDays() {


    $.get('/get-pro-rata-days', {
        "commence_date": $('#commence_date').val(),
        "expiry_date": $('#expiry_date').val(),
      },
      function(data) {

        $.each(data, function(key, value) {
          if (data["OK"]) {
            //console.log(data.days);
            $('#period_days').val(data.days);

          } else {
            //sweetAlert("Drug failed to be added!");
          }
        });

      }, 'json');

  }


  function setEndDate() {

    var myrating = $('#rating_factor').val();
    //alert(myrating);

    $.get('/get-insurance-expiry-date', {
        "commence_date": $('#commence_date').val(),
        "rating_factor": myrating,
        "period_days": $('#period_days').val(),
        "expiry_date": $('#expiry_date').val(),
        "policy_product": $('#policy_product').val()

      },
      function(data) {

        $.each(data, function(key, value) {
          if (data["OK"]) {
            // sweetAlert("Premium Payable : ", data["period_to"], "info");
            //alert(data.period_to);
            $('#expiry_date').val(data.period_to);


          } else {
            //sweetAlert("Drug failed to be added!");
          }
        });

      }, 'json');

  }



  function loadBondTemplate() {


    $.get('/load-bond-template', {
        "bond_risk": $('#bond_risk_type').val()
      },
      function(data) {

        $('#bond_template').empty();
        $.each(data, function() {
          $('#bond_template').append($('<option></option>').val(this['name']).html(this['name']));
        });

      }, 'json');
  }

  function CustomReference() {
    $('#custom_bonds').hide();

    $('#valid_until_box').hide();
    $('#bond_exchange_rate_box').hide();
    $('#valid_until_box').hide();
    $('#bond_exchange_rate_box').hide();

    if ($('#bond_risk_type').val() == "Particular Bond" || $('#bond_risk_type').val() == "Exportation Bond" || $('#bond_risk_type').val() == "Warehouse Bond" || $('#bond_risk_type').val() == "Temporary Importation/exportation Bond" || $('#bond_risk_type').val() == "Re-Exportation Bond") {
      $('#custom_bonds').show();
      $("#bond_label_1").text("Origin (Bonded Warehouse No.)");
      $("#bond_label_2").text("Destination (Bonded Warehouse No.)");
    } else if ($('#bond_risk_type').val() == "Removal Bond" || $('#bond_risk_type').val() == "General Premises Bond" || $('#bond_risk_type').val() == "General Premises Bond ") {
      $('#custom_bonds').show();
      $("#bond_label_1").text("Warehouse No.");
      $("#bond_label_2").text("Warehouse Location");
      //show vessel 
    } else if ($('#bond_risk_type').val() == "Ships Spares Bond") {
      $('#custom_bonds').show();
      $("#bond_label_1").text("Origin (Bonded Warehouse No.)");
      $("#bond_label_2").text("Destination (Bonded Warehouse No.)");
      $("#bond_label_3").text("Vessel");
      $('#bond_exchange_rate_box').show();

      //show vessel 
    } else if ($('#bond_risk_type').val() == "Advance Mobilization/payment Bond" || $('#bond_risk_type').val() == "Bid Bond") {
      $('#custom_bonds').show();
      $("#bond_label_1").text("Contract Reference No.");
      $("#bond_label_2").text("Percentage of Contract Price");
      //show vessel 
    } else if ($('#bond_risk_type').val() == "Performance Bond") {
      $('#custom_bonds').show();
      $("#bond_label_1").text("Contract Reference No.");
      $("#bond_label_2").text("Percentage of Contract Price");
      $("#bond_label_3").text("Exchange Rate (For AGENDA 111)");
      $('#valid_until_box').show();
      $('#bond_exchange_rate_box').show();

      //show vessel 
    } else {
      $('#custom_bonds').hide();
    }

  }

  function getproductform() {

    //$('#policy_number').val(''); 
    $('#btn_view_schedule').hide();
    $('#btn_add_extra').hide();


    if ($('#policy_product').val() == "Motor Insurance") {
      $('#motorinsurance').show();
      $('#fireinsurance').hide();
      $('#travelinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').show();
      $("motorcharges").prop("disabled", false);
      $('#nonmotorcharges').hide();
      $("nonmotorcharges").prop("disabled", true);
      $('#bonddate').hide();
      loadRisk();
    } else if ($('#policy_product').val() == "Fire Insurance") {
      $('#fireinsurance').show();
      $('#motorinsurance').hide();
      $('#travelinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#personalaccidentinsurance').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();
    } else if ($('#policy_product').val() == "Travel") {
      $('#travelinsurance').show();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#personalaccidentinsurance').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#mortgage_protection').hide();
      $('#funeral_form').hide();
      $('#group_term_life').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();

    } else if ($('#policy_product').val() == "Personal Accident Insurance") {
      $('#personalaccidentinsurance').show();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();


    } else if ($('#policy_product').val() == "Bond Insurance") {
      $('#bondinsurance').show();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').show();


    } else if ($('#policy_product').val() == "Marine Insurance") {
      $('#marineinsurance').show();
      $('#bondinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();



    } else if ($('#policy_product').val() == "Liability Insurance") {
      $('#marineinsurance').hide();
      $('#bondinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#liabilityinsurance').show();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();



    } else if ($('#policy_product').val() == "Engineering Insurance") {
      $('#marineinsurance').hide();
      $('#bondinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').show();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $("#transaction_date").prop("disabled", true);
      $('#bonddate').hide();



    } else if ($('#policy_product').val() == "General Accident Insurance") {
      $('#marineinsurance').hide();
      $('#bondinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').show();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();



    } else if ($('#policy_product').val() == "Health Insurance") {
      $('#marineinsurance').hide();
      $('#bondinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').show();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();

    } else if ($('#policy_product').val() == "Life Insurance") {
      $('#marineinsurance').hide();
      $('#bondinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').show();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();

    } else if ($('#policy_product').val() == "") {
      $('#motorinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#travelinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();

    } else if ($('#policy_product').val() == "Funeral" || $('#policy_product').val() == "Gualife Funeral Plan") {
      $('#motorinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#travelinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#funeral_form').show();

      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();

    } else if ($('#policy_product').val() == "Group Term Life") {
      $('#motorinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#travelinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#funeral_form').hide();
      $('#group_term_life').show();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();

    } else if ($('#policy_product').val() == "Mortgage Protection") {
      $('#motorinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#travelinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#bondinsurance').hide();
      $('#marineinsurance').hide();
      $('#liabilityinsurance').hide();
      $('#funeral_form').hide();
      $('#group_term_life').hide();
      $('#mortgage_protection').show();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();

    } else {
      $('#marineinsurance').hide();
      $('#bondinsurance').hide();
      $('#personalaccidentinsurance').hide();
      $('#travelinsurance').hide();
      $('#fireinsurance').hide();
      $('#motorinsurance').hide();
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#liabilityinsurance').show();
      $('#funeral_form').hide();
      $('#contractorallrisk').hide();
      $('#generalaccident').hide();
      $('#group_term_life').hide();
      $('#mortgage_protection').hide();
      $('#healthinsurance').hide();
      $('#lifeinsurance').hide();
      $('#motorcharges').hide();
      $("motorcharges").prop("disabled", true);
      $('#nonmotorcharges').show();
      $("nonmotorcharges").prop("disabled", false);
      $('#bonddate').hide();



    }
  }



  function showFireblanket() {

    //alert($('#fire_risk_covered').val());
    //$('#fire_risk_covered').val('');   

    if ($('#fire_risk_covered').val() == "Assets All Risk") {
      $('#fireblanket').show();

    } else if ($('#policy_product').val() == "Business Combine") {
      $('#fireblanket').show();
    } else {
      $('#fireblanket').hide();
    }
  }



  function disableUpperLowerTextform() {
    $('#policy_text').hide();
    $('#documentnumber').hide();
    $('#policy_status').prop("disabled", true);
  }


  function getcoinsuranceform() {

    if ($('#policy_sales_type').val() == "Coinsurance Outward" || $('#policy_sales_type').val() == "Reinsurance Inward" || $('#policy_sales_type').val() == "Coinsurance Inward") {

      $('#coinsurance').show();

      $('#transaction_type').val("R/I FAC. In Premium").prop("disabled", true);
      $("coinsurance").prop("disabled", true);
      $('#clauseform').hide();
      $('#sticker_number_form').hide();
      $("#premium_due_motor_final").prop("disabled", false);
      $("#premium_due_non_motor_final").prop("disabled", false);
      $("#commission_rate_motor").prop("disabled", false);
      $("#commission_rate_non_motor").prop("disabled", false);


    } else {

      $('#coinsurance').hide();
      $("coinsurance").prop("disabled", true);
      $('#clauseform').show();
      $('#sticker_number_form').show();
      $('#transaction_type').val("First Premium").prop("disabled", true);
      $("#premium_due_motor_final").prop("disabled", true);
      $("#premium_due_non_motor_final").prop("disabled", true);
      $("#commission_rate_motor").prop("disabled", true);
      $("#commission_rate_non_motor").prop("disabled", true);

    }
  }
</script>

<script>
  // function getinthousands(event) 
  // {
  //     var number = this.value;
  //     this.value = number.toLocaleString('en');

  //     alert(this.value);

  // }

  /*
  function  getcomprehensiveform() 
  {

    //alert($('#policy_product').val());
     if( $('#preferedcover').val() == "Comprehensive")
      {
        $('#vehicle_buy_back_excess').val('Excess Is Applicable');
        $('#motorinsurancecomprehensive').show();
        $('#vehicle_value').prop('disabled', false);
        $('#vehicle_buy_back_excess').prop('disabled', false);
        $('#vehicle_tppdl_value').prop('disabled', false);

        $('#vehicle_body_type').prop('disabled', false);
        $('#vehicle_chassis_number').prop('disabled', false);
        $('#vehicle_cubic_capacity').prop('disabled', false);
        $('#vehicle_make_year').prop('disabled', false);
        getVehicleUsage();
      }




      else if( $('#preferedcover').val() == "Third party")
      {
        $('#vehicle_buy_back_excess').val('Excess Is Not Applicable');
        $('#default_excess').val(0);
        
        $('#motorinsurancecomprehensive').hide();
        $('#vehicle_value').prop('disabled', true);
        $('#vehicle_buy_back_excess').prop('disabled', true);
        $('#vehicle_tppdl_value').prop('disabled', false);
   
        $('#vehicle_value').val('');
        $('#vehicle_chassis_number').prop('disabled', false);
        getVehicleUsage();

        
        
       }





       else if( $('#preferedcover').val() == "Third Party Fire & Theft")
      {
        $('#vehicle_buy_back_excess').val('Excess Is Applicable');
        $('#motorinsurancecomprehensive').hide();
        $('#vehicle_value').prop('disabled', false);
        $('#vehicle_buy_back_excess').prop('disabled', false);
        $('#vehicle_tppdl_value').prop('disabled', false);
        $('#vehicle_body_type').prop('disabled', false);
        $('#vehicle_chassis_number').prop('disabled', false);
        $('#vehicle_cubic_capacity').prop('disabled', false);
        $('#vehicle_make_year').prop('disabled', false);
        getVehicleUsage();
       }

       else if( $('#preferedcover').val() == "")
      {
       
         $('#motorinsurancecomprehensive').hide();
       }

     else
     {
        $('#motorinsurancecomprehensive').hide();
    }
  }

   */
  function getcomprehensiveform() {

    //alert($('#policy_product').val());
    if ($('#preferedcover').val() == "Comprehensive") {
      $('#vehicle_buy_back_excess').val('Excess Is Applicable');
      $('#motorinsurancecomprehensive').show();
      $('#vehicle_value').prop('disabled', false);
      $('#vehicle_buy_back_excess').prop('disabled', false);
      $('#collision_value').val(0);
      $('#collision_div').hide();
      getExcessStatus();

      $('#vehicle_tppdl_value').prop('disabled', false);

      $('#vehicle_body_type').prop('disabled', false);
      $('#vehicle_chassis_number').prop('disabled', false);
      $('#vehicle_cubic_capacity').prop('disabled', false);
      $('#vehicle_make_year').prop('disabled', false);
      getVehicleUsage();
    } else if ($('#preferedcover').val() == "Third party" || $('#preferedcover').val() == "Collision") {
      $('#vehicle_buy_back_excess').val('Excess Is Not Applicable');
      $('#default_excess').val(0);

      getExcessStatus();

      $('#motorinsurancecomprehensive').hide();
      $('#vehicle_value').prop('disabled', true);
      $('#vehicle_buy_back_excess').prop('disabled', true);
      $('#vehicle_tppdl_value').prop('disabled', false);

      $('#vehicle_value').val('');
      $('#vehicle_chassis_number').prop('disabled', false);
      getVehicleUsage();

      if ($('#preferedcover').val() == "Collision") {
        $('#collision_div').show();
        $('#collision_value').val(6000);
      } else {
        $('#collision_div').hide();
        $('#collision_value').val(0);
      }

    } else if ($('#preferedcover').val() == "Third Party Fire & Theft") {
      $('#vehicle_buy_back_excess').val('Excess Is Applicable');
      $('#motorinsurancecomprehensive').hide();
      $('#collision_value').val(0);
      $('#collision_div').hide();
      $('#vehicle_value').prop('disabled', false);
      $('#vehicle_buy_back_excess').prop('disabled', false);

      getExcessStatus();


      $('#vehicle_tppdl_value').prop('disabled', false);
      $('#vehicle_body_type').prop('disabled', false);
      $('#vehicle_chassis_number').prop('disabled', false);
      $('#vehicle_cubic_capacity').prop('disabled', false);
      $('#vehicle_make_year').prop('disabled', false);
      getVehicleUsage();
    } else if ($('#preferedcover').val() == "") {

      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#collision_value').val(0);
    } else {
      $('#motorinsurancecomprehensive').hide();
      $('#collision_div').hide();
      $('#collision_value').val(0);
    }
  }
</script>





<script type="text/javascript">
  function getBeneficiary() {

    $.get('/load-beneficiary', {
        "bene_gender": $('#bene_gender').val()
      },
      function(data) {

        $('#bene_relationship').empty();
        $.each(data, function() {
          $('#bene_relationship').append($('<option></option>').val(this['type']).html(this['type']));
        });

      }, 'json');
  }


  function fillmandatory() {
    if ($('#customer_number').val() == "") {
      swal("Please select a customer ", 'Fill all fields', "error");
    } else if ($('#policy_insurer').val() == "") {
      swal("Please select an insurer ", 'Fill all fields', "error");
    } else if ($('#policy_product').val() == "") {
      swal("Please select a product", 'Fill all fields', "error");
    } else if ($('#policy_type').val() == "") {
      swal("Please select excess ", 'Fill all fields', "error");
    }



  }

  function thousands_separators(num) {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
  }




  function computePremiumOldTariff() {

    if ($('#preferedcover').val() == "") {
      document.getElementById('preferedcover').focus();
      swal("Please select cover ", 'Fill all fields', "error");
    } else if ($('#charge_type').val() == "") {
      document.getElementById('charge_type').focus();
      swal("Please charging scheme ", 'Fill all fields', "error");
    } else if ($('#vehicle_use').val() == "") {
      document.getElementById('vehicle_use').focus();
      swal("Please select use ", 'Fill all fields', "error");
    } else if ($('#vehicle_risk').val() == "") {
      document.getElementById('vehicle_risk').focus();
      swal("Please select risk ", 'Fill all fields', "error");
    } else if ($('#vehicle_value').val() == 0 && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_value').focus();
      swal("Enter vehicle value ", 'Fill all fields', "error");
    } else if ($('#vehicle_buy_back_excess').val() == "" && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_buy_back_excess').focus();
      swal("Please select excess ", 'Fill all fields', "error");
    }
    //else if($('#vehicle_registration_number').val()=="")
    //{document.getElementById('vehicle_registration_number').focus(); swal("Enter vehicle number ",'Fill all fields', "error");}
    //else if($('#vehicle_model').val()=="")
    //{document.getElementById('vehicle_model').focus(); swal("Please select model ",'Fill all fields', "error");}
    //else if($('#vehicle_make').val()=="")
    //{document.getElementById('vehicle_make').focus(); swal("Please select make ",'Fill all fields', "error");}
    //else if($('#vehicle_body_type').val()=="")
    // {document.getElementById('vehicle_body_type').focus(); swal("Please select body type ",'Fill all fields', "error");}
    else if ($('#vehicle_seating_capacity').val() == "") {
      document.getElementById('vehicle_seating_capacity').focus();
      swal("Please enter seat number ", 'Fill all fields', "error");
    } else if ($('#vehicle_cubic_capacity').val() == 0) {
      document.getElementById('vehicle_cubic_capacity').focus();
      swal("Please enter cubic capacity ", 'Fill all fields', "error");
    } else if ($('#vehicle_ncd').val() == "") {
      document.getElementById('vehicle_ncd').focus();
      swal("Please select ncd ", 'Fill all fields', "error");
    } else if ($('#vehicle_fleet_discount').val() == "") {
      document.getElementById('vehicle_fleet_discount').focus();
      swal("Please select fleet discount ", 'Fill all fields', "error");
    } else {
      //alert($('#policy_currency').val());

      $.get('/compute-motor', {



          "policy_sales_type": $('#policy_sales_type').val(),
          "preferedcover": $('#preferedcover').val(),
          "vehicle_value": $('#vehicle_value').val(),
          "vehicle_currency": $('#policy_currency').val(),
          "vehicle_buy_back_excess": $('#vehicle_buy_back_excess').val(),
          "vehicle_use": $('#vehicle_use').val(),
          "vehicle_tppdl_value": $('#vehicle_tppdl_value').val(),
          "vehicle_risk": $('#vehicle_risk').val(),
          "vehicle_make_year": $('#vehicle_make_year').val(),
          "vehicle_seating_capacity": $('#vehicle_seating_capacity').val(),
          "vehicle_cubic_capacity": $('#vehicle_cubic_capacity').val(),
          "vehicle_ncd": $('#vehicle_ncd').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "endorsement_date": $('#commence_date').val(),
          "issue_date": $('#issue_date').val(),
          "rating_factor": $('#rating_factor').val(),
          "charge_cubic_capacity": $('#charge_cubic_capacity').prop('checked'),
          "charge_age": $('#charge_age').prop('checked'),
          "vehicle_fleet_discount": $('#vehicle_fleet_discount').val()


        },
        function(data) {

          $.each(data, function(key, value) {

            swal("Premium : " + $('#policy_currency').val() + ' ' + thousands_separators(data["Premium"]), '', "info");
            $('#gross_premium').val(data.gross_premium);
            $('#premium_due_motor').val(data.Premium);
            $('#motor_discount').val(0);
            $('#premium_due_motor_final').val(data.Premium);
            $('#commission_rate_motor').val(data.commission);
            $('#suminsured').val(data.suminsured);
            $('#contribution').val(data.contribution);
            $('#loading').val(data.loading);
            $('#netpremium').val(data.netpremium);
            $('#ncd').val(data.ncd);
            $('#fleet').val(data.fleet);
            $('#officepremium').val(data.officepremium);

            $('#ccage').val(data.ccage);
            $('#tpbasic').val(data.tpbasic);
            $('#owndamage').val(data.owndamage);

            $('#ccage_edit').val(data.ccage);
            $('#tpbasic_edit').val(data.tpbasic);
            $('#owndamage_edit').val(data.owndamage);


            $('#execessbought').val(data.execessbought);
            $('#excess_charge_rate').val(data.excess_charge_rate);
            $('#vehicle_tppdl_value_converted').val(data.tppdl_value);

          });

        }, 'json');
    }
  }


  function computePremiumNewTariff() {

    var tp_cover_type = ["Third party", "Collision"];

    if ($('#preferedcover').val() == "") {
      document.getElementById('preferedcover').focus();
      swal("Please select cover ", 'Fill all fields', "error");
    } else if ($('#charge_type').val() == "") {
      document.getElementById('charge_type').focus();
      swal("Please charging scheme ", 'Fill all fields', "error");
    } else if ($('#vehicle_use').val() == "") {
      document.getElementById('vehicle_use').focus();
      swal("Please select use ", 'Fill all fields', "error");
    } else if ($('#vehicle_risk').val() == "") {
      document.getElementById('vehicle_risk').focus();
      swal("Please select risk ", 'Fill all fields', "error");
    } else if ($('#vehicle_value').val() == 0 && !(tp_cover_type.includes($('#preferedcover').val()))) {
      document.getElementById('vehicle_value').focus();
      swal("Enter vehicle value ", 'Fill all fields', "error");
    } else if ($('#vehicle_buy_back_excess').val() == "" && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
      document.getElementById('vehicle_buy_back_excess').focus();
      swal("Please select excess ", 'Fill all fields', "error");
    }
    //else if($('#vehicle_registration_number').val()=="")
    //   {document.getElementById('vehicle_registration_number').focus(); swal("Enter vehicle number ",'Fill all fields', "error");}
    // else if($('#vehicle_model').val()=="")
    //   {document.getElementById('vehicle_model').focus(); swal("Please select model ",'Fill all fields', "error");}
    // else if($('#vehicle_make').val()=="")
    //   {document.getElementById('vehicle_make').focus(); swal("Please select make ",'Fill all fields', "error");}
    // else if($('#vehicle_body_type').val()=="")
    //   {document.getElementById('vehicle_body_type').focus(); swal("Please select body type ",'Fill all fields', "error");}
    else if ($('#vehicle_seating_capacity').val() == "") {
      document.getElementById('vehicle_seating_capacity').focus();
      swal("Please enter seat number ", 'Fill all fields', "error");
    } else if ($('#vehicle_cubic_capacity').val() == 0) {
      document.getElementById('vehicle_cubic_capacity').focus();
      swal("Please enter cubic capacity ", 'Fill all fields', "error");
    } else if ($('#vehicle_ncd').val() == "") {
      document.getElementById('vehicle_ncd').focus();
      swal("Please select ncd ", 'Fill all fields', "error");
    } else if ($('#vehicle_fleet_discount').val() == "") {
      document.getElementById('vehicle_fleet_discount').focus();
      swal("Please select fleet discount ", 'Fill all fields', "error");
    } else {
      $.get('/compute-motor-new', {

          //alert($('#vehicle_ncd').val());

          "policy_sales_type": $('#policy_sales_type').val(),
          "preferedcover": $('#preferedcover').val(),
          "vehicle_value": $('#vehicle_value').val(),
          "vehicle_currency": $('#policy_currency').val(),
          "vehicle_buy_back_excess": $('#vehicle_buy_back_excess').val(),
          "vehicle_use": $('#vehicle_use').val(),
          "vehicle_tppdl_value": $('#vehicle_tppdl_value').val(),
          "vehicle_risk": $('#vehicle_risk').val(),
          "vehicle_make_year": $('#vehicle_make_year').val(),
          "vehicle_seating_capacity": $('#vehicle_seating_capacity').val(),
          "vehicle_cubic_capacity": $('#vehicle_cubic_capacity').val(),
          "vehicle_ncd": $('#vehicle_ncd').val(),
          "insurance_period": $('#insurance_period').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "endorsement_date": $('#commence_date').val(),
          "issue_date": $('#issue_date').val(),
          "endorsement_status": $('#endorsement_status').val(),
          "rating_factor": $('#rating_factor').val(),
          "voluntary_excess": $('#voluntary_excess').val(),
          "charge_cubic_capacity": $('#charge_cubic_capacity').prop('checked'),
          "charge_age": $('#charge_age').prop('checked'),
          "vehicle_fleet_discount": $('#vehicle_fleet_discount').val(),
          "collision_value": $('#collision_value').val()


        },
        function(data) {

          $.each(data, function(key, value) {

            swal("Premium : " + $('#policy_currency').val() + ' ' + thousands_separators(data["Premium"]), '', "info");
            $('#gross_premium').val(data.gross_premium);
            $('#premium_due_motor').val(data.Premium);
            $('#motor_discount').val(0);
            $('#premium_due_motor_final').val(data.Premium);
            $('#commission_rate_motor').val(data.commission);
            $('#suminsured').val(data.suminsured);
            $('#contribution').val(data.contribution);
            $('#loading').val(data.loading);
            $('#netpremium').val(data.netpremium);
            $('#ncd').val(data.ncd);
            $('#fleet').val(data.fleet);
            $('#officepremium').val(data.officepremium);

            $('#ccage').val(data.ccage);
            $('#tpbasic').val(data.tpbasic);
            $('#owndamage').val(data.owndamage);

            $('#ccage_edit').val(data.ccage);
            $('#tpbasic_edit').val(data.tpbasic);
            $('#owndamage_edit').val(data.owndamage);


            $('#execessbought').val(data.execessbought);
            $('#excess_charge_rate').val(data.excess_charge_rate);
            $('#vehicle_tppdl_value_converted').val(data.tppdl_value);
            $('#vehicle_pa_value').val(data.pa_value);

          });

        }, 'json');
    }
  }


  function computePremiumNotStep() {


    if ($('#policy_product').val() == "Motor Insurance" & $('#vehicle_registration_number').val() != "")

    {

      if ($('#preferedcover').val() == "") {
        swal("Please select cover ", 'Fill all fields', "error");
        //document.preferedcover.name.focus();
      } else if ($('#vehicle_value').val() == "" && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
        swal("Enter vehicle value ", 'Fill all fields', "error");
      } else if ($('#vehicle_currency').val() == "") {
        swal("Please select currency ", 'Fill all fields', "error");
      } else if ($('#vehicle_buy_back_excess').val() == "" && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
        swal("Please select excess ", 'Fill all fields', "error");
      } else if ($('#vehicle_use').val() == "") {
        swal("Please select use ", 'Fill all fields', "error");
      } else if ($('#vehicle_risk').val() == "") {
        swal("Please select risk ", 'Fill all fields', "error");
      } else if ($('#vehicle_seating_capacity').val() == "") {
        swal("Please enter seat number ", 'Fill all fields', "error");
      } else if ($('#vehicle_cubic_capacity').val() == "" && ($('#preferedcover').val() != "Third party" || $('#preferedcover').val() != "Collision")) {
        swal("Please enter cubic capacity ", 'Fill all fields', "error");
      } else if ($('#vehicle_ncd').val() == "") {
        swal("Please select ncd ", 'Fill all fields', "error");
      } else if ($('#vehicle_fleet_discount').val() == "") {
        swal("Please select fleet discount ", 'Fill all fields', "error");
      } else {
        $.get('/compute-motor', {

            // alert($('#agency').val());

            "policy_sales_type": $('#policy_sales_type').val(),
            "preferedcover": $('#preferedcover').val(),
            "vehicle_value": $('#vehicle_value').val(),
            "vehicle_currency": $('#policy_currency').val(),
            "vehicle_buy_back_excess": $('#vehicle_buy_back_excess').val(),
            "vehicle_use": $('#vehicle_use').val(),
            "vehicle_tppdl_value": $('#vehicle_tppdl_value').val(),
            "vehicle_risk": $('#vehicle_risk').val(),
            "vehicle_make_year": $('#vehicle_make_year').val(),
            "vehicle_seating_capacity": $('#vehicle_seating_capacity').val(),
            "vehicle_cubic_capacity": $('#vehicle_cubic_capacity').val(),
            "vehicle_ncd": $('#vehicle_ncd').val(),
            "insurance_period": $('#insurance_period').val(),
            "commence_date": $('#commence_date').val(),
            "expiry_date": $('#expiry_date').val(),
            "issue_date": $('#issue_date').val(),

            "vehicle_fleet_discount": $('#vehicle_fleet_discount').val()


          },
          function(data) {

            $.each(data, function(key, value) {

              swal("Premium : " + $('#policy_currency').val() + ' ' + thousands_separators(data["Premium"]), '', "info");
              $('#gross_premium').val(data.gross_premium);
              $('#premium_due_motor').val(data.Premium);
              $('#motor_discount').val(0);
              $('#premium_due_motor_final').val(data.Premium);
              $('#commission_rate_motor').val(data.commission);
              $('#suminsured').val(data.suminsured);
              $('#contribution').val(data.contribution);
              $('#loading').val(data.loading);
              $('#netpremium').val(data.netpremium);
              $('#ncd').val(data.ncd);
              $('#fleet').val(data.fleet);
              $('#officepremium').val(data.officepremium);

              $('#ccage').val(data.ccage);
              $('#tpbasic').val(data.tpbasic);
              $('#owndamage').val(data.owndamage);

              $('#ccage_edit').val(data.ccage);
              $('#tpbasic_edit').val(data.tpbasic);
              $('#owndamage_edit').val(data.owndamage);


              $('#execessbought').val(data.execessbought);
              $('#excess_charge_rate').val(data.excess_charge_rate);
              $('#vehicle_tppdl_value_converted').val(data.tppdl_value);

            });

          }, 'json');
      }
    } else {
      //swal("No Premium Computed", 0, "info");
    }

  }
</script>

<script type="text/javascript">
  function loadNCD() {


    $.get('/load-ncd-rate', {
        "vehicle_use": $('#vehicle_use').val(),
        "vehicle_risk": $('#vehicle_risk').val()
      },
      function(data) {

        $('#vehicle_ncd').empty();
        $.each(data, function() {
          $('#vehicle_ncd').append($('<option></option>').val(this['type']).html(this['type']));
        });

      }, 'json');
  }
</script>

<script type="text/javascript">
  function loadRisk() {


    $.get('/load-risk', {
        "vehicle_use": $('#vehicle_use').val()
      },
      function(data) {

        $('#vehicle_risk').empty();
        $.each(data, function() {
          $('#vehicle_risk').append($('<option></option>').val(this['risk']).html(this['risk']));
        });

      }, 'json');
  }


  function getVehicleUsage() {


    $.get('/load-vehicle-usage', {
        "vehicle_risk": $('#vehicle_risk').val(),
        "vehicle_buy_back_excess": $('#vehicle_buy_back_excess').val(),

      },
      function(data) {

        $('#vehicle_use').empty();
        $.each(data, function(key, value) {

          var usage = data.getVehicleUsage;
          var excess_rate = data.getExcessRate;
          // alert(usage);
          //$('#transaction_type').val("R/I FAC. In Premium").prop("disabled", true);
          $('#vehicle_use').val(usage).prop("disabled", true);
          $('#default_excess').val(excess_rate).prop("disabled", true);

          if ($('#vehicle_buy_back_excess').val() == 'Voluntary Excess Applicable') {
            $('#voluntary_excess').val(0).prop("disabled", false);
            $("#voluntary_excess").attr("readonly", false);
          } else {
            $('#voluntary_excess').val(0).prop("disabled", true);
            $("#voluntary_excess").attr("readonly", true);
          }

          computenonMotorPremium();
          loadNCD();

        });

      }, 'json');
  }
</script>

<script type="text/javascript">
  function getvehicleratingstate() {

    $.get('/get-vehicle-rating-state', {
        "vehicle_registration_number": $('#vehicle_registration_number').val()

      },
      function(data) {

        //$('#charge_type').empty();
        $.each(data, function(key, value) {

          var rating = data.getVehicleRating;
          // var excess_rate = data.getExcessRate;
          // alert(rating);
          //$('#transaction_type').val("R/I FAC. In Premium").prop("disabled", true);
          $('#charge_type').val(rating).prop("disabled", true);;
          //$('#default_excess').val(excess_rate).prop("disabled", true);


          computenonMotorPremium();
          loadNCD();


        });

      }, 'json');

  }




  function vehicleexiststatus() {

    $.get('/get-vehicle-availability', {
        "vehicle_registration_number": $('#vehicle_registration_number').val()

      },
      function(data) {

        $.each(data, function(key, value) {
          if (data["OK"]) {
            $('#vehicle_registration_number').val('');
            swal("Vehicle " + $('#vehicle_registration_number').val() + " already exist in system!");
            $('#vehicle_registration_number').val('');
          } else if (data["Wrong Format"]) {
            $('#vehicle_registration_number').val('');
            swal("Vehicle number " + $('#vehicle_registration_number').val() + " does not match standard for approved numbers!");
            $('#vehicle_registration_number').val('');
          } else {

            getvehicleratingstate();
            //swal("Drug failed to be added!");
          }
        });

      }, 'json');

  }

  function vehicleratingstatus() {



  }


  function alphaOnly(event) {
    var key = event.keyCode;
    return ((key >= 65 && key <= 90) || key == 8 || key == 9 || key == 46 || key == 32 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 189 || key == 109);
  };

  function numericOnly(event) {
    var key = event.keyCode;
    return (key >= 48 && key <= 57);
  };


  function clearriskdescfield() {

    $('#property_address').val('');
    $('#property_description').val('');
    $('#engineering_risk_description').val('');
    $('#liability_risk_description').val('');
    $('#accident_risk_description').val('');

  }




  function stickerexiststatus() {

    $.get('/get-sticker-availability', {
        "sticker_number": $('#brown_card_number').val()

      },
      function(data) {

        $.each(data, function(key, value) {
          if (data["OK"]) {


          } else {
            //$("#sticker_number").val('').trigger('change');
            swal("Brown Card " + $('#brown_card_number').val() + " is not available in the system or has been used!");
            $('#brown_card_number').val('');
          }
        });

      }, 'json');


  }




  function checkCustomerType() {


    //alert($('#policy_product').val());

    // if($('#policy_product').val()=="Bond Insunrace" && $('#customer_type').val()=="Individual")
    // {

    //      $('#policy_product').val('');
    //      swal("You cannot create a " +  $('#policy_product').val() +" policy for this customer !");
    // }

    // else
    // {

    // }



  }


  // function loadRiskNumber()
  // {


  //     $.get('/load-risk-number',
  //       {
  //         "policy_number": $('#policy_number').val()
  //       },
  //       function(data)
  //       { 

  //         $('#property_number_item').empty();
  //         $.each(data, function () 
  //         {           
  //         $('#property_number_item').append($('<option></option>').val(this['risk_number']).html(this['risk_number']));
  //         });

  //      },'json');      


  // }

  function showAccidentMeansOfConveyance() {
    if ($('#accident_risk_type').val() == "Goods In Transit") {
      $('#accident_means_of_conveyance_div').show()
    } else {
      $('#accident_means_of_conveyance_div').hide()
    }
  }





  function loadWorkgroups() {

    //alert($('#policy_branch').val());

    $.get('/load-workgroup', {

        "policy_branch": $('#policy_branch').val()
      },
      function(data) {

        $('#policy_workgroup').empty();


        $.each(data, function() {
          $('#policy_workgroup').append($('<option></option>').val(this['type']).html(this['type']));
        });

      }, 'json');
  }


  function loadWorkgroupSources() {


    $.get('/load-workgroup-sources', {
        "policy_workgroup": $('#policy_workgroup').val()
      },
      function(data) {

        $('#policy_sales_type').empty();
        $('#agency').empty();

        $.each(data, function() {
          $('#policy_sales_type').append($('<option></option>').val(this['type']).html(this['type'] == 'Reinsurance Inward' ? 'Facultative Inward' : this['type']));
        });

      }, 'json');
  }






  function loadIntermediary() {


    $.get('/load-intermediary', {
        "policy_sales_type": $('#policy_sales_type').val()
      },
      function(data) {

        $('#agency').empty();
        $.each(data, function() {
          $('#agency').append($('<option></option>').val(this['agentcode']).html(this['agentcode'] + ' - ' + this['agentname']));
        });

      }, 'json');
  }


  function loadPortImportation() {


    $.get('/load-port-importation', {
        "marine_country_of_importation": $('#marine_country_of_importation').val()
      },
      function(data) {

        $('#marine_port_of_loading').empty();
        $.each(data, function() {
          $('#marine_port_of_loading').append($('<option></option>').val(this['port']).html(this['port']));
        });

      }, 'json');
  }

  function loadPortDestination() {

    $.get('/load-port-destination', {
        "marine_country_of_destination": $('#marine_country_of_destination').val()
      },
      function(data) {

        $('#marine_port_of_destination').empty();
        $.each(data, function() {
          $('#marine_port_of_destination').append($('<option></option>').val(this['port']).html(this['port']));
        });

      }, 'json');
  }


  function loadPolicyClause() {


    $.get('/load-policy-clause', {
        "policy_product": $('#policy_product').val()
      },
      function(data) {



        //$('#policy_clause').select2('val', ["value1", "value2", "value3"]);

        $('#policy_clause').empty();
        $.each(data, function() {
          $('#policy_clause').append($('<option></option>').val(this['description']).html(this['type']));


          addCompulsoryClauses();


        });

      }, 'json');
  }
</script>

<script type="text/javascript">
  function loadModels() {


    $.get('/load-vehicle-model', {
        "vehicle_make": $('#vehicle_make').val()
      },
      function(data) {

        $('#vehicle_model').empty();
        $.each(data, function() {
          $('#vehicle_model').append($('<option></option>').val(this['model']).html(this['model']));
        });

      }, 'json');
  }
</script>

<script type="text/javascript">
  function loadInsurer() {


    $.get('/load-insurer', {
        "policy_type": $('#policy_type').val()
      },
      function(data) {

        $('#policy_insurer').empty();
        $.each(data, function() {
          $('#policy_insurer').append($('<option></option>').val(this['name']).html(this['name']));
        });

      }, 'json');
  }





  function loadBeneficiary() {
    $.get('/get-beneficiary', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#beneficiaryTable tbody').empty();
        $.each(data, function(key, value) {
          $('#beneficiaryTable tbody').append('<tr><td>' + value['category'] + '</td><td>' + value['fullname'] + '</td><td>' + value['address'] + '</td><td>' + value['relationship'] + '</td><td>' + value['age'] + '</td><td>' + value['gender'] + '</td><td>' + value['share_of_benefit'] + '</td><td>' + value['created_by'] + '</td><td>' + value['created_on'] + '</td><td><a href="#new-beneficiary" class="bootstrap-modal-form-open" data-toggle="modal"><i onclick="editBeneficiary(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeBeneficiary(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }


  function loadMandate() {

    $.get('/get-mandate', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#mandateTable tbody').empty();
        $.each(data, function(key, value) {
          $('#mandateTable tbody').append('<tr><td>' + value['institution'] + '</td><td>' + value['staff_number'] + '</td><td>' + value['premium'] + '</td><td>' + value['created_by'] + '</td><td>' + value['created_on'] + '</td><td><a href="#new-mandate" class="bootstrap-modal-form-open" data-toggle="modal"><i onclick="editMandate(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeMandate(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }



  function addBeneficiary() {


    if ($('#bene_fullname').val() == "") {
      document.getElementById('bene_fullname').focus();
      sweetAlert("Please enter fullname ", 'Fill all fields', "error");
    } else if ($('#bene_gender').val() == "") {
      document.getElementById('bene_gender').focus();
      sweetAlert("Please select gender ", 'Fill all fields', "error");
    } else if ($('#bene_relationship').val() == "") {
      document.getElementById('bene_relationship').focus();
      sweetAlert("Please select relationship", 'Fill all fields', "error");
    } else if ($('#bene_date_of_birth').val() == "") {
      document.getElementById('bene_date_of_birth').focus();
      sweetAlert("Please select date of birth", 'Fill all fields', "error");
    } else if ($('#bene_share_of_benefit').val() == "") {
      document.getElementById('bene_share_of_benefit').focus();
      sweetAlert("Please enter share", 'Fill all fields', "error");
    } else {

      $.get('/add-policy-beneficiary', {
          "beneficiarykey": $('#beneficiarykey').val(),
          "policy_number": $('#policy_number').val(),
          "bene_category": $('#bene_category').val(),
          "bene_fullname": $('#bene_fullname').val(),
          "bene_address": $('#bene_address').val(),
          "bene_phone": $('#bene_phone').val(),
          "bene_relationship": $('#bene_relationship').val(),
          "bene_gender": $('#bene_gender').val(),
          "bene_date_of_birth": $('#bene_date_of_birth').val(),
          "bene_share_of_benefit": $('#bene_share_of_benefit').val(),
          "bene_date_of_nomination": $('#bene_date_of_nomination').val()

        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Beneficiary details successfully saved!");
              loadBeneficiary();
              //checkIfBeneficiaryExist();

              $('#new-beneficiary').modal('toggle');

              // $("#btnengineering").html('Click to Add New Item');
              // $("#engineering_unit").val('').trigger('change');
              $('#bene_category').val('');
              $('#bene_fullname').val('');
              $('#bene_address').val('');
              $('#bene_relationship').val('').trigger('change');
              $('#bene_gender').val('').trigger('change');
              $('#bene_share_of_benefit').val('');

            } else if (data["Surplus"]) {
              sweetAlert("Share has been over apportioned, please check values!");
            } else {
              toastr.error("Beneficiary details failed to save!");

            }
          });

        }, 'json');
    }
  }


  function addMandate() {

    if ($('#mandate_institution').val() == "") {
      document.getElementById('mandate_institution').focus();
      sweetAlert("Please enter institution name ", 'Fill all fields', "error");
    } else if ($('#mandate_staff_number').val() == "") {
      document.getElementById('mandate_staff_number').focus();
      sweetAlert("Please enter staff_number ", 'Fill all fields', "error");
    } else if ($('#mandate_department').val() == "") {
      document.getElementById('mandate_department').focus();
      sweetAlert("Please enter department", 'Fill all fields', "error");
    } else if ($('#mandate_premium').val() == "") {
      document.getElementById('mandate_premium').focus();
      sweetAlert("Please enter premium amount", 'Fill all fields', "error");
    } else {
      $.get('/add-mandate', {
          "mandatekey": $('#mandatekey').val(),
          "policy_number": $('#policy_number').val(),
          "mandate_fullname": $('#mandate_institution').val(),
          "mandate_institution": $('#mandate_institution').val(),
          "plan": $('#policy_sales_channel').val(),
          "mandate_staff_number": $('#mandate_staff_number').val(),
          "mandate_department": $('#mandate_department').val(),
          "mandate_location": $('#mandate_location').val(),
          "mandate_premium_date": $('#mandate_premium_date').val(),
          "mandate_premium": $('#mandate_premium').val()

        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Mandate details successfully saved!");
              loadMandate();

              $('#new-mandate').modal('toggle');
              // $('#get-customer-form').modal('toggle')

            } else {
              toastr.error("Duplicate Mandate / Error saving manadate!");

            }
          });

        }, 'json');
    }
  }



  function editMandate(id) {
    $.get("/edit-mandate", {
        "id": id
      },
      function(json) {
        $('#new-mandate input[name="mandatekey"]').val(json.mandatekey);
        $('#new-mandate select[name="mandate_institution"]').val(json.mandate_institution).select2();
        $('#new-mandate input[name="mandate_staff_number"]').val(json.mandate_staff_number);
        $('#new-mandate input[name="mandate_department"]').val(json.mandate_department);
        $('#new-mandate input[name="mandate_location"]').val(json.mandate_location);
        $('#new-mandate input[name="mandate_premium_date"]').val(json.mandate_premium_date);
        $('#new-mandate input[name="mandate_premium"]').val(json.mandate_premium);
        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });
  }

  function editBeneficiary(id) {

    $.get("/edit-beneficiary", {
        "id": id
      },
      function(json) {
        $('#new-beneficiary input[name="beneficiarykey"]').val(json.beneficiarykey);
        $('#new-beneficiary input[name="bene_fullname"]').val(json.bene_fullname);
        $('#new-beneficiary select[name="bene_category"]').val(json.bene_category);
        $('#new-beneficiary select[name="bene_gender"]').val(json.bene_gender);
        $('#new-beneficiary select[name="bene_relationship"]').val(json.bene_relationship);
        $('#new-beneficiary input[name="bene_phone"]').val(json.bene_phone);
        $('#new-beneficiary input[name="bene_address"]').val(json.bene_address);
        $('#new-beneficiary input[name="bene_date_of_birth"]').val(json.bene_date_of_birth);
        $('#new-beneficiary input[name="bene_share_of_benefit"]').val(json.bene_share_of_benefit);
        $('#new-beneficiary input[name="bene_date_of_nomination"]').val(json.bene_date_of_nomination);
        //}
      }, 'json').fail(function(msg) {
      alert(msg.status + " " + msg.statusText);
    });
  }



  function removeMandate(id) {
    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-mandate', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadMandate();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');
      });
  }




  function removeBeneficiary(id) {
    swal({
        title: "Enter your password to delete item!",
        text: "Are you sure you want to delete item ?",
        type: "input",
        inputType: "password",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Please enter your password"
      },
      function(inputValue) {
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Please enter your password!");
          return false
        }

        //alert($('#cover_type').val());
        $.get('/delete-beneficiary', {
            "ID": id,
            "mypassword": inputValue
          },
          function(data) {

            $.each(data, function(key, value) {
              if (value == "OK") {
                loadBeneficiary();
                swal.close();
              } else {
                swal("Cancelled", name + " failed to delete. Password verification failed", "error");
              }
            });

          }, 'json');
      });
  }



  function computeFuneralPremium() {
    // alert($('#policy_sales_channel').val());

    $.get('/compute-funeral-premium', {                                                                                                                                                                                                                                                             

        "risk_type": $('#policy_sales_channel').val(),
        "funeral_member_type": $('#funeral_member_type').val(),
        "funeral_benefit": $('#funeral_benefit').val(),
        "funeral_member_age": $('#funeral_member_age').val(),
        "funeral_member_gender": $('#funeral_member_gender').val(),

      },
      function(data) {

        $.each(data, function(key, value) {

          $('#funeral_premium').val(data.premium_due);

        });

      }, 'json');

  }

  function addFuneralDetails() {


    if ($('#funeral_member_type').val() == "") {
      document.getElementById('funeral_member_type').focus();
      sweetAlert("Please select/enter an item  ", 'Fill all fields', "error");
    } else if ($('#funeral_member_name').val() == "") {
      document.getElementById('funeral_member_name').focus();
      sweetAlert("Please enter member name  ", 'Fill all fields', "error");
    } else {

      $.get('/add-funeral-schedule', {
          "policy_number": $('#policy_number').val(),
          "funeral_risk_type": $('#policy_sales_channel').val(),
          "funeral_member_name": $('#funeral_member_name').val(),
          "funeral_account_number": $('#customer_number').val(),
          "currency": $('#policy_currency').val(),
          "funeral_member_type": $('#funeral_member_type').val(),
          "funeral_benefit": $('#funeral_benefit').val(),
          "funeral_member_age": $('#funeral_member_age').val(),
          "funeral_member_gender": $('#funeral_member_gender').val(),
          "funeral_premium": $('#funeral_premium').val(),
          "commence_date": $('#commence_date').val(),
          "expiry_date": $('#expiry_date').val(),
          "issue_date": $('#issue_date').val(),
          "funeralkey": $('#funeralkey').val()
        },
        function(data) {

          $.each(data, function(key, value) {
            if (data["OK"]) {
              toastr.success("Funeral schedule successfully saved!");
              loadFuneralDetails();

              $('#funeral_member_type').val('');
              $('#funeralkey').val('');
              $('#funeral_benefit').val('0');
              $("#btnfuneral").html('Click to Add New Item');
              $("#funeral_member_type").val('').trigger('change');

            } else {
              toastr.error("Funeral schedule failed to save!");

            }
          });

        }, 'json');
    }

  }


  function loadFuneralDetails() {


    $.get('/get-funeral-schedule', {
        "policy_number": $('#policy_number').val()
      },
      function(data) {

        $('#funeralScheduleTable tbody').empty();
        $.each(data, function(key, value) {
          $('#funeralScheduleTable tbody').append('<tr><td>' + value['unit'] + '</td><td>' + value['beneficiary'] + '</td><td>' + thousands_separators(value['sum_insured']) + '</td><td>' + value['age'] + '</td><td>' + value['net_premium'] + '</td><td>' + value['created_by'] + '</td><td>' + value['created_on'] + '</td><td><a a href="#"><i onclick="editFuneral(' + value['id'] + ')" class="fa fa-pencil"></i></a></td><td><a a href="#"><i onclick="removeFuneral(' + value['id'] + ')" class="fa fa-trash-o"></i></a></td></tr>');
        });

      }, 'json');
  }

  function loadMemberAge() {

    var name = $('#mycustomername').val();
    var gender = $('#mycustomergender').val();

    if ($('#funeral_member_type').val() == 'Primary Insured' || $('#funeral_member_type').val() == 'Personal Accidental Indemnity Rider' || $('#funeral_member_type').val() == 'Hospital Cover Per Day') {
      $('#funeral_member_name').val(name);
      $('#funeral_member_gender').val(gender).select2();
      $("#funeral_member_name").prop("disabled", true);

    } else {
      $('#funeral_member_name').val('');
      $("#funeral_member_name").prop("disabled", false);
    }


    $.get('/compute-funeral-age', {
        "funeral_member_type": $('#funeral_member_type').val(),
        "primary_insured_age": $('#primary_insured_age').val()
      },
      function(data) {

        $('#funeral_member_age').empty();
        $.each(data, function(index, item) {
          $('#funeral_member_age').append($('<option></option>').val(item).html(item));
        });

      }, 'json');
  }


  function setMandatePremium() {


    var mandateamount = $('#premium_due_non_motor_final').val();
    $('#new-mandate input[name="mandate_premium"]').val(mandateamount);


  }
</script>

<script type="text/javascript">
  function loadinsurancetype() {


    $.get('/load-product', {
        "policy_type": $('#policy_type').val()
      },
      function(data) {

        $('#policy_product').empty();
        $.each(data, function() {
          $('#policy_product').append($('<option></option>').val(this['type']).html(this['type']));
        });

      }, 'json');
  }
</script>
<script type="text/javascript">
  $(function() {
    $('#departure_date').daterangepicker({
      "minDate": moment('2010-06-14'),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#arrival_date').daterangepicker({
      "minDate": moment(),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });

    $('#travel_family_date_of_birth').daterangepicker({
      "maxDate": moment(),
      "startDate": moment().subtract(3, 'years'),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });

  });
</script>

<script type="text/javascript">
  $(function() {
    $('#bene_date_of_birth').daterangepicker({
      "minDate": moment('1920-01-01 '),
      "maxDate": moment(),
      "singleDatePicker": true,
      "showDropdowns": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#voyage_date').daterangepicker({
      "minDate": moment('2010-06-14'),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#vehicle_register_date').daterangepicker({
      "minDate": moment('2010-06-14'),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#vehicle_lta_upload').daterangepicker({
      "minDate": moment('2010-06-14'),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#vehicle_lta_transmission').daterangepicker({
      "minDate": moment('2010-06-14'),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {
    $('#vehicle_register_date').daterangepicker({
      "minDate": moment('2010-06-14 0'),
      "maxDate": moment(),
      "singleDatePicker": true,
      "autoApply": true,
      "showDropdowns": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
      }
    });
  });
</script>



<style>
  .row1 {
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
  }
</style>