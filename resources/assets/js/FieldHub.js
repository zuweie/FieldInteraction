var FieldHub = {};

(function(myObject){
	
	var subjects = {};
	
	myObject.publish = function(sub, params){
		if (!subjects[sub]) {
			return false;
		}
		var subscribers = subjects[sub];
		subscribers.forEach(function(subscriber){
			subscriber.handle(params);
		});
	};
	
	myObject.subscribe = function(sub, func) {
		
		if (!subjects[sub]) {
			subjects[sub] = [];
		}
		subjects[sub].push({
			handle : func
		});
	};
	
	myObject.triggerlog = function (msg, event, data) {
		return {msg : msg, event: event, data : data};
	}
})(FieldHub);