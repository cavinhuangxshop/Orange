import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import cash from '../../js/Integrated/integratedList';

var data = cash.getConfig();

function counter(state = data , action) {
  switch (action.type) {
    /*获取集成项目数据*/
    case 'get_integrated_data':
       cash.nowPage = action.nowPage;//当前页数
       if(action.data !== undefined){
    	   //ajax获取到的数据处理
    	   cash.setIntegratedData(action.data.data);
      	   cash.totalRows = action.data.total;
       };      
       return cash.getData();     
       
/***************************************添加集成项目*****************************************************/       
    /*显示添加集成项目框*/
    case 'fixed_add_show':
       cash.fixedAddShow = true;
       return cash.getData(); 
    /*隐藏添加集成项目框*/
    case 'fixed_add_hide':
       cash.fixedAddShow = false;
       return cash.getData();
    /*集成项目框值改变*/
    case 'add_input_change':
       cash.fixedAddData[action.name] = action.value;
       return cash.getData();
    /*集成项目框值改变*/
    case 'add_type_change':
       cash.fixedAddData['type'][action.num] = cash.fixedAddData['type'][action.num] == 0 ? action.num+1 : 0;
       return cash.getData();   
    /*集成项目添加成功*/   
    case 'tender_add':
       var data    = cash.fixedAddData;
           data.id = action.data.id;
       cash.tableData[cash.nowPage].push(data);
       cash.fixedAddShow = false;
       return cash.getData();

/***************************************集成项目详情*****************************************************/       
    /*显示集成项目详情*/
    case 'fixed_details_show':
	   cash.fixedDetailsData = cash.tableData[cash.nowPage][action.id];
       cash.fixedDetailsShow = true;
       return cash.getData();
    /*隐藏编集成项目详情*/
    case 'fixed_details_hide':
       cash.fixedDetailsShow = false;
       return cash.getData(); 
       
/***************************************集成项目编辑*****************************************************/       
    /*显示集成项目编辑*/
    case 'fixed_edit_show':
	   cash.fixedEditData = cash.tableData[cash.nowPage][action.id];	   	   
       cash.fixedEditShow = true;
       return cash.getData();
    /*隐藏编集成项目编辑*/
    case 'fixed_edit_hide':
       cash.fixedEditShow = false;
       return cash.getData();     
    /*集成项目框值改变*/
    case 'edit_input_change':
       cash.fixedEditData[action.name] = action.value;
       return cash.getData(); 
    /*集成项目框类型值改变*/
    case 'edit_type_change':
       cash.fixedEditData['type'][action.num] = cash.fixedEditData['type'][action.num] == 0 ? action.num+1 : 0;
       console.log(cash.fixedEditData['type']);
       return cash.getData();         
       /*管理员信息修改成功*/   
    case 'integrated_edit':
       cash.tableData[cash.nowPage][cash.fixedEditData.id] = cash.fixedEditData;
       cash.fixedEditShow = false;
       return cash.getData();
    default:
       return state;
  }
}

// Store
const store = createStore(counter,applyMiddleware(thunk));
export default store;

