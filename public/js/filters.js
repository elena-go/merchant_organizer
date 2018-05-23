
function Filters(key, userId){
    var id = key + '_' + userId;
    this.clear = function(){
        sessionStorage.setItem(id, '{}');
    };
    this.load = function(){
        var data = sessionStorage.getItem(id);
        return data ? JSON.parse(data) : {};
    };
    this.save = function(obj){
        if (obj) sessionStorage.setItem(id, JSON.stringify(obj));
    };       
};
