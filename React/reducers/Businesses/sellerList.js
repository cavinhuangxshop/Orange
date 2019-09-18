import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
var data = {
	  'table_data' : {},
	  'table_data_state' : false,
	  
	  'search' : {
		  'shop_name' : ''
	  }, 
	
	  'data_page' :{
		  totalRows : '',
		  nowPage   : 1,
		  listRows  : 12,
		  rollPage  : 7
	  }
} 

function counter(state = data , action) {
  switch (action.type) {
    case 'get_data':
       return action.data; 
    case 'deletedata':
       return action.data;
    case 'changestatus':
       return action.data;
    case 'get_goods_model':
       return action.data;
    case 'get_category':
       return action.data;
    case 'change_model_status':
       return action.data;
    default:
       return state;
  }
}

// Store
const store = createStore(counter,applyMiddleware(thunk));
export default store;

