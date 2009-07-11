package referenceData
{
	import mx.collections.ArrayCollection;
	import mx.rpc.events.ResultEvent;
	import mx.rpc.remoting.mxml.RemoteObject;
	
	import util.Async;
	
	public class BunkerTypeService
	{
		
		private static var _instance: BunkerTypeService;
		
		public static function getInstance() : BunkerTypeService {
			if (_instance == null) {
				trace("Creating BunkerTypeService instance");
				_instance = new BunkerTypeService();
			}
			return _instance;
		}
		
		private var bunkerTypeService: RemoteObject;
		
		[Bindable]
		public var bunkerTypes: ArrayCollection = new ArrayCollection();

		[Bindable]
		public var bunkerTypes2: ArrayCollection = new ArrayCollection();
		
		private function getRemoteObject() : RemoteObject {
			var result: RemoteObject;
			
			trace("Creating kwl.BunkerTypeService RemoteObject");
			result = new RemoteObject();
			result.source = "kwl.BunkerTypeService";
			result.destination = "amfphp";
			
			return result;
		}
		
		private function result_findAll(event : ResultEvent) : void {
			trace("kwl.BunkerTypeService findAll result triggered");
			var result: Array = event.result as Array;
			bunkerTypes.source = result;
			bunkerTypes2.source = [{type:null, label:"Alle"}].concat(result);
		}
		
		public function BunkerTypeService() {
			bunkerTypeService = getRemoteObject();
			Async.call(bunkerTypeService.findAll(), result_findAll);
		}

	}
}