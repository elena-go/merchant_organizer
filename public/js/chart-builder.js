function ChartBuilder(selector, data, config)
{
    function color(hex, alpha)
    {
        var index = hex[0] == '#' ? 1 : 0;

        var r = parseInt(hex.substr(index, 2), 16);
        var g = parseInt(hex.substr(index + 2, 2), 16);
        var b = parseInt(hex.substr(index + 4, 2), 16);

        return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
    }

    function map(data, name)
    {
        var index = data.columns.indexOf(name);
        
        return index < 0 ? [] : data.rows.map(function (row)
        {
            return row[index];

        });
    }

    this.append = function(item)
    {
        this.data.rows.push(item);

        this.refresh(this.data);
    }

    this.draw = function(data)
    {
        return new Chart(e[0], config);
    }

    this.load = function ()
    {
        if (!config.data || !config.data.datasets || !data || !data.rows || !data.views || !this.refresh(data, true)) return;

        this.data = data;

        for (var i in config.data.datasets)
        {
            if (config.data.datasets[i].opacity)
            {
                for (var prop in config.data.datasets[i])
                {
                    if (prop.match('Color$'))
                    {
                        config.data.datasets[i][prop] = color(config.data.datasets[i][prop], config.data.datasets[i].opacity);
                    }
                }
            }
        }

        instance = this.draw();
    }

    this.refresh = function(data, load)
    {
        if (config.data.datasets.length == data.views.length)
        {
            for (var i in config.data.datasets)
            {
                config.data.datasets[i].data = map(data, data.views[i]);
            }
            
            if (data.columns && config.data.labelsColumn && (load && !config.data.labels || !load))
            {
                config.data.labels = map(data, config.data.labelsColumn);
            }

            if (load) return true;

            instance.update();
        }
    }

    var e = $(selector), instance; if (e.length == 1) this.load();
}