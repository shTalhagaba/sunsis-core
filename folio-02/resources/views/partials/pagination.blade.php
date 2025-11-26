@if($collection->count() > 0)
{{ $collection->appends($_GET)->links() }}<br>
Showing
 <strong>{{ ($collection->currentpage()-1)*$collection->perpage()+1 }}</strong>
  to
   <strong>{{ $collection->currentpage()*$collection->perpage() > $collection->total() ? $collection->total() : $collection->currentpage()*$collection->perpage() }}</strong>
    of
     <strong>{{ $collection->total() }}</strong>
     entries
@endif