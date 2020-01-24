<style>

    .emp-profile{
        padding: 3%;
        margin-top: 3%;
        margin-bottom: 3%;
        border-radius: 0.5rem;
        background: #fff;
    }
    .profile-img{
        text-align: center;
    }
    .profile-img img{
        width: 70%;
        height: 100%;
    }
    .profile-img .file {
        position: relative;
        overflow: hidden;
        margin-top: -20%;
        width: 70%;
        border: none;
        border-radius: 0;
        font-size: 15px;
        background: #212529b8;
    }
    .profile-img .file input {
        position: absolute;
        opacity: 0;
        right: 0;
        top: 0;
    }
    .profile-head h5{
        color: #333;
    }
    .profile-head h6{
        color: #0062cc;
    }
    .profile-edit-btn{
        border: none;
        border-radius: 1.5rem;
        width: 70%;
        padding: 2%;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
    }
    .proile-rating{
        font-size: 12px;
        color: #818182;
        margin-top: 5%;
    }
    .proile-rating span{
        color: #495057;
        font-size: 15px;
        font-weight: 600;
    }
    .profile-head .nav-tabs{
        margin-bottom:5%;
    }
    .profile-head .nav-tabs .nav-link{
        font-weight:600;
        border: none;
    }
    .profile-head .nav-tabs .nav-link.active{
        border: none;
        border-bottom:2px solid #0062cc;
    }
    .profile-work{
        padding: 14%;
        margin-top: -15%;
    }
    .profile-work p{
        font-size: 12px;
        color: #818182;
        font-weight: 600;
        margin-top: 10%;
    }
    .profile-work a{
        text-decoration: none;
        color: #495057;
        font-weight: 600;
        font-size: 14px;
    }
    .profile-work ul{
        list-style: none;
    }
    .profile-tab label{
        font-weight: 600;
    }
    .profile-tab p{
        font-weight: 600;
        color: #0062cc;
    }
    </style>
    
    
    
    
    <div class="container emp-profile">
      <form>
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS52y5aInsxSm31CvHOFHWujqUx_wWTS9iM6s7BAm21oEN_RiGoog" alt=""/>
                    <div class="file btn btn-lg btn-primary">
                        Change Photo
                        <input type="file" name="file"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="profile-head">
                            <h5>
                              {{$client->store}}
                            </h5>
                            <h6>
                              {{$client->location}}
                            </h6>
                            <p class="proile-rating">{{$division->name}}</p>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Orders</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2">
              <button type="button" class="profile-edit-btn" onclick="editEmployee({{$client->id}},null)">Edit</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                
            </div>
            <div class="col-md-8">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Owner</label>
                            </div>
                            <div class="col-md-6">
                              <p>{{$client->owner}}</p>
                            </div>
                        </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label>E-mail address</label>
                          </div>
                          <div class="col-md-6">
                              <p>{{$client->email}}</p>
                          </div>
                      </div>
                      
                      <div class="row">
                          <div class="col-md-6">
                              <label>Location</label>
                          </div>
                          <div class="col-md-6">
                            <p>{{$client->location}}</p>
                          </div>
                      </div>
                      
                        <div class="col-md-6">
                          <p>{{$client->location}}</p>
                        </div>
                    </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label>Phone</label>
                          </div>
                          <div class="col-md-6">
                            <p>{{$client->contact}}</p>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label>Contact Person</label>
                          </div>
                          <div class="col-md-6">
                            <p>{{$client->contact_person}}</p>
                          </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <table class="table table-hover" id="clientOrders">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{$order->delivery_date}}</td>
                                        <td>{{number_format($order->total_amount,2)}}</td>
                                        <td>{{number_format($order->balance,2)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </form>           
    </div>
    <script>
        $('#clientOrders').DataTable();
    </script>