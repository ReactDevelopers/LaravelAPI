@extends('layouts.main')

@section('content')
<div class="container">
<div class="innner-wrapper">
<form class="form_style" method="POST" action="" id="todoForm" autocomplete="off">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-3 col-sm-4 col-xs-12">    
          <div class="heightbox">
            <select name="category" class='form-control select-filter' data-placeholder="Select Industry">
              @foreach($categoryList as $k => $v)
              <?php
              $value1 = '';
              if(old('category'))
                $value1 = old('category');
              ?>
              <option value="{!!$k!!}"{{ (collect($value1)->contains($v)) ? 'selected':'' }} >{!!$v!!}</option>
              @endforeach 
            </select>
          </div>
      </div>
      <div class="col-md-7 col-sm-7 col-xs-12">
          <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <input type="text" class="form-control" name="name" placeholder="Type todo item name" id="name">
            <span class="help-block">
                <strong id="ajaxResponsename" class="errortext"></strong>
            </span>
        </div>
      </div>
      <div class="col-md-2 col-sm-2 col-xs-12">
        <div class="heightbox">
             <button type="submit" class="btn btn-success" id="todobtn">Add</button>
        </div>
      </div>
    </div>
  </form>
    <div class="inner-wrapper">
      <table id="users-table" class="table table-bordered table-responsive">
        <thead>
        <tr>
          <th width="5%">#</th>
          <th width="30%">Todo item name</th>
          <th width="23%">Category</th>
          <th width="23%">Timestamp</th>
          <th>Actions</th>
        </tr>
        </thead>  
      </table>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
  $(function(){
    $('.dataTables_filter').hide();
  });
var dataTableInstance = '';
   $(document).on('change','#filter', function(e) {
      dataTableInstance.draw();      
   });

   $(function(){
       dataTableInstance = $('#users-table').DataTable({
           paging: true,
           searching: true,
           processing: true,
           serverSide: true,
           lengthChange:false,
           
           ajax: {
               url: "{!! url('todo') !!}",
               data: function (d) {
                  d.filter_value = $('select[name=filter]').val();
               },
           },
           columns : [
                      {
                         "className": 'sno',
                         "orderable": false,
                         "data": null,
                         "defaultContent": '',
                         "searchable": false
                      },
                      { data: 'name', name: 'name' },
                      { data: 'catname', name: 'catname' },
                      { data: 'date', name: 'date' },
                      { 
                           "className": 'action', 
                           "orderable": false, 
                           "data": 'action', 
                           "defaultContent": '', 
                           "searchable": false, 
                           "orderable": false
                      }
           ],
           order:[
               [0, "ASC"]
           ],
           columnDefs: [
               {
                   "targets": 0,
                   "data": null,
                   "render": function (data, type, full, meta) {
                       return parseFloat(meta.row) + parseFloat(1) + parseFloat(meta.settings._iDisplayStart);
                   }
               }
           ],
       });
   }); 

</script>
@endpush
