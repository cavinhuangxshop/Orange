import React from 'react';
import {connect} from 'react-redux';
import actions from '../../../actions/GroupBuy/groupBuyList';
 /* *
   * 联动分类树
   * */
class fixedDetails extends React.Component{	
    render(){  
    	const {value , dispatch } = this.props;   
    	var data = value.fixedDetailsData;    	
    	var check_status  = data.check_status   == 1?true:false; 
    	if(data.id === undefined){
    		return(
    	        <div id = 'fixed_edit' className = {value.fixedDetailsShow?'':'fixed_edit_hide'}></div>
    	    );
    	}
        return(
            <div id = 'fixed_edit' className = {value.fixedDetailsShow?'':'fixed_edit_hide'}>
        	    <div className = "fixed_edit_wraper">
        	         <h2 className = 'fixed_edit_title'>团购商品详情</h2>
        	         <ul className = 'fixed_edit_content'>
        	             <table cellspacing = "0">
			   	              <tr>
			                      <td className = 'label'>商品名称：</td>
			                      <td>{data.goods_name}</td>
			                      <td className = 'label'>商品价格:</td>
			                      <td>{data.goods_price}</td>
			                  </tr>
			   	              <tr>
		                          <td className = 'label'>商品标题：</td>
		                          <td>{data.title}</td>
			                      <td className = 'label'>申请位置:</td>
			                      <td>{data.ad_1 == 1 ? '首页' : data.ad_1 == 2 ? '推荐位' : ''}</td>
	                          </tr>
			   	              <tr>
			                      <td className = 'label'>团购价格：</td>
			                      <td>{data.group_price}</td>
			                      <td className = 'label'>申请时间:</td>
			                      <td>{data.create_time}</td>
		                      </tr>
			   	              <tr>
			                      <td className = 'label'>团购开始时间：</td>
			                      <td>{data.start_time}</td>
			                      <td className = 'label'>持续时间:</td>
			                      <td>{data.time}</td>
	                          </tr>
			   	              <tr>
			                      <td className = 'label'>团购图片：</td>
			                      <td colSpan = '3'><img className = 'img' src = {data.img.substring(1)}></img></td>
                              </tr>
			                  <tr>
			                      <td className = 'label'>审核结果：</td>
			                      <td>通过</td>
			                      <td className = 'label'>审核时间：</td>
			                      <td>{data.check_time}</td>
			                  </tr>
			                  <tr>
				                  <td colSpan = '4'>
			    	                  <a className = 'btn' href = 'javascript:;' onClick = {() => {dispatch(actions.fixedDetailsHide())}}>关闭</a>
				                  </td>
			                  </tr>
        	             </table>           
        	         </ul>         
        	    </div>
        	</div>  
        )
    }
}

function mapStateToProps(state) {
   return {
      value: state
   }
}
export default connect(mapStateToProps)(fixedDetails);