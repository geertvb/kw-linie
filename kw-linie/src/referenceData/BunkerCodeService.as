package referenceData
{
	import mx.collections.ArrayCollection;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.remoting.mxml.RemoteObject;
	
	import util.Async;
	
	public class BunkerCodeService
	{
		
		private static var _instance: BunkerCodeService;
		
		public static function getInstance() : BunkerCodeService {
			if (_instance == null) {
				trace("Creating BunkerCodeService instance");
				_instance = new BunkerCodeService();
			}
			return _instance;
		}
		
		private var bunkerCodeService: RemoteObject;
		
		[Bindable]
		public var bunkerCodes: ArrayCollection = new ArrayCollection();

		[Bindable]
		public var bunkerCodes2: ArrayCollection = new ArrayCollection();
		
		private function getRemoteObject() : RemoteObject {
			var result: RemoteObject;
			
			trace("Creating kwl.BunkerCodeService RemoteObject");
			result = new RemoteObject();
			result.source = "kwl.BunkerCodeService";
			result.destination = "amfphp";
			
			return result;
		}
		
		private function result_findAll(event : ResultEvent) : void {
			trace("kwl.BunkerCodeService findAll result triggered");
			var result: Array = event.result as Array;
			bunkerCodes.source = result;
			bunkerCodes2.source = [{code:null, label:"Alle"}].concat(result);
		}
		
		public function BunkerCodeService() {
			bunkerCodeService = getRemoteObject();
			Async.call(bunkerCodeService.findAll(), result_findAll);
		}

	}
}