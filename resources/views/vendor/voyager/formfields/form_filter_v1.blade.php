<style>
   ul.dropdown-menu.filter-form.show{
      margin-left: -25px !important;
   }
   .cus-btn:focus {
      box-shadow: none !important;
   }
   .filter-icon {
      border-left: 0.6px solid #d5d5d5;;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem 1.5rem;
      border-top: 0.6px solid #d5d5d5;
      border-bottom: 0.6px solid #d5d5d5;
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
   }
   .filter-box {
      display: flex;
      justify-content: center;
      align-items: center;
      border-left: 0.6px solid #d5d5d5;
      border-top: 0.6px solid #d5d5d5;
      border-bottom: 0.6px solid #d5d5d5;
   }

   .reset-filter {
      display: flex;
      justify-content: center;
      align-items: center;
      border: 0.6px solid #d5d5d5;
      border-top-right-radius: 10px;
      border-bottom-right-radius: 10px;
   }
   .bar-filter1 {
      display: flex;
      justify-content: center;
      align-items: center;
      border: 0.6px solid #d5d5d5;
      border-top-right-radius: 10px;
      border-bottom-right-radius: 10px;
   }
</style>


<form method="get" class="form-search">
    <div class="d-flex m-4" style="height: 4rem;">
        <div class="filter-icon">
            <span>
                <i class="fa-solid fa-filter"></i>
            </span>
        </div>
        <div class="d-flex" id="filter-content">
            
        </div>
        <input type="hidden" id="data-filter" name="data_filter">
        <div class="reset-filter" style="padding: 1rem;">
            <button class="btn cus-btn fw-bold clear-filter">
                <span class="text-danger align-middle"><i class="fa-solid fa-arrows-rotate"></i></span>
                <span class="text-danger">Reset Filter</span>    
            </button>
        </div>

        {{--
        <div class="bar-filter1" style="padding: 1rem;" id="filter-content">
            <button class="btn cus-btn fw-bold">
                <span class="text-dark align-middle"><i class="fa-solid fa-bars"></i></span>
            </button>
        </div>
        --}}

        
    </div>

    
</form>