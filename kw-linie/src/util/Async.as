package util
{

	import mx.controls.Alert;
	import mx.rpc.AsyncToken;
	import mx.rpc.events.FaultEvent;
	
	public class Async {
				
		public static function defaultFault(event : FaultEvent) : void {
			Alert.show("fault " + event.fault);
		}
		
		public static function call(at: AsyncToken, onResult: Function, onFault: Function = null) : void {
			if (onFault == null) {
				onFault = defaultFault;
			}
			at.addResponder(new mx.rpc.Responder(onResult, onFault));
		}
	
	}
	
}