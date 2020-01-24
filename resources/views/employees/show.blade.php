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
                          {{$employee->firstname}} {{$employee->middlename[0]}}. {{$employee->lastname}}
                        </h5>
                        <h6>
                          {{$employee->position}}
                        </h6>
                        <p class="proile-rating">AGENT</p>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">More Info</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
          <button type="button" class="profile-edit-btn" onclick="editEmployee({{$employee->id}},null)">Edit</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="profile-work">
                <p>DIVISIONS</p>
                @foreach ($divisions as $division)
                    <p>{{$division->name}}, {{$division->area}}</p>
                @endforeach
            </div>
        </div>
        <div class="col-md-8">
            <div class="tab-content profile-tab" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <div class="row">
                      <div class="col-md-6">
                          <label>E-mail address</label>
                      </div>
                      <div class="col-md-6">
                          <p>{{$employee->email}}</p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                          <label>Date of Birth</label>
                      </div>
                      <div class="col-md-6">
                        <p>{{date('F j, Y',strtotime($employee->birth_date))}}</p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                          <label>Address</label>
                      </div>
                      <div class="col-md-6">
                        <p>{{$employee->address}}</p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                          <label>Phone</label>
                      </div>
                      <div class="col-md-6">
                        <p>{{$employee->contact}}</p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                          <label>Salary</label>
                      </div>
                      <div class="col-md-6">
                          <p>₱ {{number_format($employee->salary)}} / mo</p>
                      </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="row">
                          <div class="col-md-6">
                              <label>Gender</label>
                          </div>
                          <div class="col-md-6">
                              <p>{{ucfirst($employee->gender)}}</p>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label>Hired</label>
                          </div>
                          <div class="col-md-6">
                              <p>{{date('M d, Y',strtotime($employee->created_at))}}</p>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label>Orders Handled</label>
                          </div>
                          <div class="col-md-6">
                              <p>{{count($orders)}}</p>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label>Clients</label>
                          </div>
                          <div class="col-md-6">
                              <p>{{count($clients)}}</p>
                          </div>
                      </div>
                      
                </div>
            </div>
        </div>
    </div>
  </form>           
</div>