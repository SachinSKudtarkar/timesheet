Extension : autorefresh

Requirement:
Every input fields should be refere from its model.
Restriction
It's Only work on update case


	
In Controller:

	On Action >>> 
	        
		Yii::import("application.extensions.yii-node.component.*");
		// Object for nodejs script
                $node_js = new CNodeJSScripts();
                $node_js->registerYiiNodeScripts();
		// setModel with its model & primary key value
                $node_js->setModel( get_class( $model ), $id);

