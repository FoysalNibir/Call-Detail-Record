function MarkerClusterer(map, opt_markers, opt_options) {
    this.extend(MarkerClusterer, google.maps.OverlayView);
    this.map_ = map;
    this.markers_ = [];
    this.clusters_ = [];
    this.sizes = [53, 56, 66, 78, 90];
    this.styles_ = [];
    this.ready_ = false;
    var options = opt_options || {};
    this.legend_ = options['legend'] || {};
    this.gridSize_ = options['gridSize'] || 60;
    this.minClusterSize_ = options['minimumClusterSize'] || 2;
    this.maxZoom_ = options['maxZoom'] || null;
    this.styles_ = options['styles'] || [];
    this.imagePath_ = options['imagePath'] ||
        this.MARKER_CLUSTER_IMAGE_PATH_;
    this.imageExtension_ = options['imageExtension'] ||
        this.MARKER_CLUSTER_IMAGE_EXTENSION_;
    this.zoomOnClick_ = true;

    if (options['zoomOnClick'] != undefined) {
        this.zoomOnClick_ = options['zoomOnClick'];
    }
    this.averageCenter_ = false;
    if (options['averageCenter'] != undefined) {
        this.averageCenter_ = options['averageCenter'];
    }
    this.setupStyles_();
    if (opt_markers && opt_markers.length) {
        this.setupLegend_(opt_markers);
    }
    this.setMap(map);
    this.prevZoom_ = this.map_.getZoom();
    var that = this;
    google.maps.event.addListener(this.map_, 'zoom_changed', function () {
        var zoom = that.map_.getZoom();
        if (that.prevZoom_ != zoom) {
            that.prevZoom_ = zoom;
            that.resetViewport();
        }
    });
    google.maps.event.addListener(this.map_, 'idle', function () {
        that.redraw();
    });
    if (opt_markers && opt_markers.length) {
        this.addMarkers(opt_markers, false);
    }
}
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ =
    'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/' +
    'images/m';
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_ = 'png';
MarkerClusterer.prototype.extend = function (obj1, obj2) {
    return (function (object) {
        for (var property in object.prototype) {
            this.prototype[property] = object.prototype[property];
        }
        return this;
    }).apply(obj1, [obj2]);
};
MarkerClusterer.prototype.onAdd = function () {
    this.setReady_(true);
};
MarkerClusterer.prototype.draw = function () {
};
MarkerClusterer.prototype.setupStyles_ = function () {
    if (this.styles_.length) {
        return;
    }

    for (var i = 0, size; size = this.sizes[i]; i++) {
        this.styles_.push({
            url: this.imagePath_ + (i + 1) + '.' + this.imageExtension_,
            height: size,
            width: size
        });
    }
};
MarkerClusterer.prototype.setupLegend_ = function (markers) {

    var colorSeries = ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00",
        "#b82e2e", "#316395", "#994499", "#22aa99", "#aaaa11", "#6633cc", "#e67300", "#8b0707", "#651067",
        "#329262", "#5574a6", "#3b3eac", "#b77322", "#16d620", "#b91383", "#f4359e", "#9c5935", "#a9c413",
        "#2a778d", "#668d1c", "#bea413", "#0c5922", "#743411"];

    var markerSymbol = {
        path: 'M256 14.316c-91.31 0-165.325 74.025-165.325 165.325 0.010 91.32 165.325 318.044 165.325 318.044s165.315-226.724 165.315-318.034c0.010-91.31-73.984-165.335-165.315-165.335zM256 245.494c-34.56 0-62.608-28.078-62.608-62.648 0-34.55 28.037-62.566 62.608-62.566 34.591 0 62.618 28.027 62.618 62.566 0 34.57-28.017 62.649-62.618 62.649z',
        fillOpacity: 1.0,
        scale: 0.065,
        anchor: new google.maps.Point(250, 500)
    };
    for (var key in this.legend_) {
        if (this.legend_.hasOwnProperty(key)) {
            var index = colorSeries.indexOf(this.legend_[key]);
            if (index > -1) {
                colorSeries.splice(index, 1);
            }
        }
    }
    var colorIndex = 0;
    for (var i = 0, marker; marker = markers[i]; i++) {
        if (!(marker.title in this.legend_)) {
            this.legend_[marker.title] = (colorSeries[colorIndex]);
            markerSymbol["fillColor"] = (colorSeries[colorIndex]);
            marker.setIcon(markerSymbol);
            colorIndex++;
        }
        else {
            markerSymbol["fillColor"] = this.legend_[marker.title];
            marker.setIcon(markerSymbol);
        }
    }
    var legend_div = document.createElement('DIV');
    legend_div.style.cssText = "margin-right: 5px; background-color: rgba(255, 255, 255, 0.9) !important; padding: 10px; width: 123px";
    this.map_.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend_div);

    for (var title in this.legend_) {
        var color = this.legend_[title];
        var color_div = document.createElement('div');
        color_div.style.cssText = "float: left; margin:0; overflow:hidden; background-color:"+ color +"; width: 12px; height: 12px;";
        legend_div.appendChild(color_div);

        var title_div = document.createElement('div');
        title_div.innerHTML = title;
        title_div.style.cssText = "padding-bottom: 5px; padding-left: 1%; float: left; color:"+ color +"!important; margin-left:0; width:80%; overflow:hidden;";
        legend_div.appendChild(title_div);


    }
};
MarkerClusterer.prototype.fitMapToMarkers = function () {
    var markers = this.getMarkers();
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, marker; marker = markers[i]; i++) {
        bounds.extend(marker.getPosition());
    }

    this.map_.fitBounds(bounds);
};
MarkerClusterer.prototype.setStyles = function (styles) {
    this.styles_ = styles;
};
MarkerClusterer.prototype.getStyles = function () {
    return this.styles_;
};
MarkerClusterer.prototype.setLegend = function (legend) {
    this.legend_ = legend;
};
MarkerClusterer.prototype.getLegend = function () {
    return this.legend_;
};
MarkerClusterer.prototype.isZoomOnClick = function () {
    return this.zoomOnClick_;
};
MarkerClusterer.prototype.isAverageCenter = function () {
    return this.averageCenter_;
};
MarkerClusterer.prototype.getMarkers = function () {
    return this.markers_;
};
MarkerClusterer.prototype.getTotalMarkers = function () {
    return this.markers_.length;
};
MarkerClusterer.prototype.setMaxZoom = function (maxZoom) {
    this.maxZoom_ = maxZoom;
};
MarkerClusterer.prototype.getMaxZoom = function () {
    return this.maxZoom_;
};
MarkerClusterer.prototype.calculator_ = function (markers, numStyles) {
    var index = 0;
    var count = markers.length;
    var dv = count;
    while (dv !== 0) {
        dv = parseInt(dv / 10, 10);
        index++;
    }

    index = Math.min(index, numStyles);
    return {
        text: count,
        index: index
    };
};
MarkerClusterer.prototype.setCalculator = function (calculator) {
    this.calculator_ = calculator;
};
MarkerClusterer.prototype.getCalculator = function () {
    return this.calculator_;
};
MarkerClusterer.prototype.addMarkers = function (markers, opt_nodraw) {
    for (var i = 0, marker; marker = markers[i]; i++) {
        this.pushMarkerTo_(marker);
    }
    if (!opt_nodraw) {
        this.redraw();
    }
};
MarkerClusterer.prototype.pushMarkerTo_ = function (marker) {
    marker.isAdded = false;
    if (marker['draggable']) {
        // If the marker is draggable add a listener so we update the clusters on
        // the drag end.
        var that = this;
        google.maps.event.addListener(marker, 'dragend', function () {
            marker.isAdded = false;
            that.repaint();
        });
    }
    this.markers_.push(marker);
};
MarkerClusterer.prototype.addMarker = function (marker, opt_nodraw) { //Hassan
    this.pushMarkerTo_(marker);
    if (!opt_nodraw) {
        this.redraw();
    }
};
MarkerClusterer.prototype.removeMarker_ = function (marker) {
    var index = -1;
    if (this.markers_.indexOf) {
        index = this.markers_.indexOf(marker);
    } else {
        for (var i = 0, m; m = this.markers_[i]; i++) {
            if (m == marker) {
                index = i;
                break;
            }
        }
    }

    if (index == -1) {
        // Marker is not in our list of markers.
        return false;
    }

    marker.setMap(null);

    this.markers_.splice(index, 1);

    return true;
};
MarkerClusterer.prototype.removeMarker = function (marker, opt_nodraw) {
    var removed = this.removeMarker_(marker);

    if (!opt_nodraw && removed) {
        this.resetViewport();
        this.redraw();
        return true;
    } else {
        return false;
    }
};
MarkerClusterer.prototype.removeMarkers = function (markers, opt_nodraw) {
    var removed = false;

    for (var i = 0, marker; marker = markers[i]; i++) {
        var r = this.removeMarker_(marker);
        removed = removed || r;
    }

    if (!opt_nodraw && removed) {
        this.resetViewport();
        this.redraw();
        return true;
    }
};
MarkerClusterer.prototype.setReady_ = function (ready) {
    if (!this.ready_) {
        this.ready_ = ready;
        this.createClusters_();
    }
};
MarkerClusterer.prototype.getTotalClusters = function () {
    return this.clusters_.length;
};
MarkerClusterer.prototype.getMap = function () {
    return this.map_;
};
MarkerClusterer.prototype.setMap = function (map) {
    this.map_ = map;
};
MarkerClusterer.prototype.getGridSize = function () {
    return this.gridSize_;
};
MarkerClusterer.prototype.setGridSize = function (size) {
    this.gridSize_ = size;
};
MarkerClusterer.prototype.getMinClusterSize = function () {
    return this.minClusterSize_;
};
MarkerClusterer.prototype.setMinClusterSize = function (size) {
    this.minClusterSize_ = size;
};
MarkerClusterer.prototype.getExtendedBounds = function (bounds) {
    var projection = this.getProjection();
    var tr = new google.maps.LatLng(bounds.getNorthEast().lat(),
        bounds.getNorthEast().lng());
    var bl = new google.maps.LatLng(bounds.getSouthWest().lat(),
        bounds.getSouthWest().lng());
    var trPix = projection.fromLatLngToDivPixel(tr);
    trPix.x += this.gridSize_;
    trPix.y -= this.gridSize_;
    var blPix = projection.fromLatLngToDivPixel(bl);
    blPix.x -= this.gridSize_;
    blPix.y += this.gridSize_;
    var ne = projection.fromDivPixelToLatLng(trPix);
    var sw = projection.fromDivPixelToLatLng(blPix);
    bounds.extend(ne);
    bounds.extend(sw);
    return bounds;
};
MarkerClusterer.prototype.isMarkerInBounds_ = function (marker, bounds) {
    return bounds.contains(marker.getPosition());
};
MarkerClusterer.prototype.clearMarkers = function () {
    this.resetViewport(true);
    this.markers_ = [];
};
MarkerClusterer.prototype.resetViewport = function (opt_hide) {
    for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
        cluster.remove();
    }
    for (var i = 0, marker; marker = this.markers_[i]; i++) {
        marker.isAdded = false;
        if (opt_hide) {
            marker.setMap(null);
        }
    }
    this.clusters_ = [];
};
MarkerClusterer.prototype.repaint = function () {
    var oldClusters = this.clusters_.slice();
    this.clusters_.length = 0;
    this.resetViewport();
    this.redraw();
    window.setTimeout(function () {
        for (var i = 0, cluster; cluster = oldClusters[i]; i++) {
            cluster.remove();
        }
    }, 0);
};
MarkerClusterer.prototype.redraw = function () {
    this.createClusters_();
};
MarkerClusterer.prototype.distanceBetweenPoints_ = function (p1, p2) {
    if (!p1 || !p2) {
        return 0;
    }

    var R = 6371; // Radius of the Earth in km
    var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
    var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d;
};
MarkerClusterer.prototype.addToClosestCluster_ = function (marker) {
    var distance = 40000; // Some large number
    var clusterToAddTo = null;
    var pos = marker.getPosition();
    for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
        var center = cluster.getCenter();
        if (center) {
            var d = this.distanceBetweenPoints_(center, marker.getPosition());
            if (d < distance) {
                distance = d;
                clusterToAddTo = cluster;
            }
        }
    }

    if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
        clusterToAddTo.addMarker(marker);
    } else {
        var cluster = new Cluster(this);
        cluster.addMarker(marker);
        this.clusters_.push(cluster);
    }
};
MarkerClusterer.prototype.createClusters_ = function () {
    if (!this.ready_) {
        return;
    }
    var mapBounds = new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),
        this.map_.getBounds().getNorthEast());
    var bounds = this.getExtendedBounds(mapBounds);

    for (var i = 0, marker; marker = this.markers_[i]; i++) {
        if (!marker.isAdded && this.isMarkerInBounds_(marker, bounds)) {
            this.addToClosestCluster_(marker);
        }
    }
};
function Cluster(markerClusterer) {
    this.markerClusterer_ = markerClusterer;
    this.map_ = markerClusterer.getMap();
    this.gridSize_ = markerClusterer.getGridSize();
    this.minClusterSize_ = markerClusterer.getMinClusterSize();
    this.averageCenter_ = markerClusterer.isAverageCenter();
    this.center_ = null;
    this.markers_ = [];
    this.bounds_ = null;

    this.legend_ = markerClusterer.getLegend();
    this.chartData_ = {};
    this.initializeChartData_();

    this.clusterIcon_ = new ClusterIcon(this, markerClusterer.getStyles(),
        markerClusterer.getGridSize());
}
Cluster.prototype.initializeChartData_ = function () {
    for (var key in this.legend_) {
        if (this.legend_.hasOwnProperty(key)) {
            this.chartData_[key] = this.legend_[key];
            this.chartData_[key] = 0;
        }
    }
};
Cluster.prototype.getChartData = function () {
    return this.chartData_;
};
Cluster.prototype.isMarkerAlreadyAdded = function (marker) {
    if (this.markers_.indexOf) {
        return this.markers_.indexOf(marker) != -1;
    } else {
        for (var i = 0, m; m = this.markers_[i]; i++) {
            if (m == marker) {
                return true;
            }
        }
    }
    return false;
};
Cluster.prototype.addMarker = function (marker) {
    if (this.isMarkerAlreadyAdded(marker)) {
        return false;
    }

    if (!this.center_) {
        this.center_ = marker.getPosition();
        this.calculateBounds_();
    } else {
        if (this.averageCenter_) {
            var l = this.markers_.length + 1;
            var lat = (this.center_.lat() * (l - 1) + marker.getPosition().lat()) / l;
            var lng = (this.center_.lng() * (l - 1) + marker.getPosition().lng()) / l;
            this.center_ = new google.maps.LatLng(lat, lng);
            this.calculateBounds_();
        }
    }
    marker.isAdded = true;
    this.markers_.push(marker);
    this.chartData_[marker.getTitle()]++;
    var len = this.markers_.length;
    if (len < this.minClusterSize_ && marker.getMap() != this.map_) {
        marker.setMap(this.map_);
    }
    if (len == this.minClusterSize_) {
        for (var i = 0; i < len; i++) {
            this.markers_[i].setMap(null);
        }
    }
    if (len >= this.minClusterSize_) {
        marker.setMap(null);
    }
    this.updateIcon();
    return true;
};
Cluster.prototype.getMarkerClusterer = function () {
    return this.markerClusterer_;
};
Cluster.prototype.getBounds = function () {
    var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
    var markers = this.getMarkers();
    for (var i = 0, marker; marker = markers[i]; i++) {
        bounds.extend(marker.getPosition());
    }
    return bounds;
};
Cluster.prototype.remove = function () {
    this.clusterIcon_.remove();
    this.markers_.length = 0;
    delete this.markers_;
};
Cluster.prototype.getSize = function () {
    return this.markers_.length;
};
Cluster.prototype.getMarkers = function () {
    return this.markers_;
};
Cluster.prototype.getCenter = function () {
    return this.center_;
};
Cluster.prototype.calculateBounds_ = function () {
    var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
    this.bounds_ = this.markerClusterer_.getExtendedBounds(bounds);
};
Cluster.prototype.isMarkerInClusterBounds = function (marker) {
    return this.bounds_.contains(marker.getPosition());
};
Cluster.prototype.getMap = function () {
    return this.map_;
};
Cluster.prototype.updateIcon = function () {
    var zoom = this.map_.getZoom();
    var mz = this.markerClusterer_.getMaxZoom();
    if (mz && zoom > mz) {
        for (var i = 0, marker; marker = this.markers_[i]; i++) {
            marker.setMap(this.map_);
        }
        return;
    }
    if (this.markers_.length < this.minClusterSize_) {
        // Min cluster size not yet reached.
        this.clusterIcon_.hide();
        return;
    }
    var numStyles = this.markerClusterer_.getStyles().length;
    var sums = this.markerClusterer_.getCalculator()(this.markers_, numStyles);
    this.clusterIcon_.setCenter(this.center_);
    this.clusterIcon_.setSums(sums);
    this.clusterIcon_.show();
};
function ClusterIcon(cluster, styles, opt_padding) {
    cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);

    this.styles_ = styles;
    this.padding_ = opt_padding || 0;
    this.cluster_ = cluster;
    this.center_ = null;
    this.map_ = cluster.getMap();
    this.div_ = null;
    this.chart_div_ = null;
    this.sums_ = null;
    this.visible_ = false;

    this.setMap(this.map_);
}
ClusterIcon.prototype.triggerClusterClick = function () {
    var markerClusterer = this.cluster_.getMarkerClusterer();
    google.maps.event.trigger(markerClusterer, 'clusterclick', this.cluster_);
    if (markerClusterer.isZoomOnClick()) {
        this.map_.fitBounds(this.cluster_.getBounds());
    }
};
ClusterIcon.prototype.onAdd = function () {
    this.div_ = document.createElement('DIV');
    this.chart_div_ = document.createElement('DIV');

    if (this.visible_) {
        var pos = this.getPosFromLatLng_(this.center_);
        this.div_.style.cssText = this.createCss(pos);
        this.div_.innerHTML = this.sums_.text;
        this.chart_div_.style.cssText = this.createCss(pos);
    }
    var panes = this.getPanes();
    panes.overlayMouseTarget.appendChild(this.div_);
    panes.overlayMouseTarget.appendChild(this.chart_div_);
    var that = this;
    google.maps.event.addDomListener(this.chart_div_, 'click', function () {
        that.triggerClusterClick();
    });

    google.maps.event.addDomListener(this.div_, 'click', function () {
        that.triggerClusterClick();
    });
};
ClusterIcon.prototype.getPosFromLatLng_ = function (latlng) {
    var pos = this.getProjection().fromLatLngToDivPixel(latlng);
    pos.x -= parseInt(this.width_ / 2, 10);
    pos.y -= parseInt(this.height_ / 2, 10);
    return pos;
};
ClusterIcon.prototype.draw = function () {
    if (this.visible_) {
        var pos = this.getPosFromLatLng_(this.center_);
        this.div_.style.top = pos.y + 'px';
        this.div_.style.left = pos.x + 'px';
        this.chart_div_.style.top = pos.y + 'px';
        this.chart_div_.style.left = pos.x + 'px';
        this.renderCharts_();
    }
};
ClusterIcon.prototype.hide = function () {
    if (this.div_) {
        this.div_.style.display = 'none';
    }
    if (this.chart_div_) {
        this.chart_div_.style.display = 'none';
    }
    this.visible_ = false;
};
ClusterIcon.prototype.show = function () {
    if (this.div_) {
        var pos = this.getPosFromLatLng_(this.center_);
        this.div_.style.cssText = this.createCss(pos);
        this.div_.style.display = '';
    }
    if (this.chart_div_) {
        this.chart_div_.style.cssText = this.createCss(pos);
        this.chart_div_.style.display = '';
        this.renderCharts_();
    }
    this.visible_ = true;
};


ClusterIcon.prototype.renderCharts_ = function () {
    var clusterChartData = this.cluster_.getChartData();
    var clusterLegend = this.cluster_.getMarkerClusterer().getLegend();
    var dataArray = [['Title', 'Count']];
    var chartColorsSeq = [];
    for (var key in clusterChartData) {
        if (clusterChartData.hasOwnProperty(key)) {
            var dataRow = [];
            dataRow.push(key);
            dataRow.push(clusterChartData[key]);
            dataArray.push(dataRow);
            chartColorsSeq.push(clusterLegend[key]);
        }
    }
    var data = google.visualization.arrayToDataTable(dataArray);
    var options = {
        fontSize: 8,
        backgroundColor: 'transparent',
        legend: 'none',
        pieHole: 0.5,
        tooltip: {text: 'value'},
        colors: chartColorsSeq,
        pieSliceText: 'none'
    };
    var chart = new google.visualization.PieChart(this.chart_div_);
    chart.draw(data, options);
};
ClusterIcon.prototype.remove = function () {
    this.setMap(null);
};
ClusterIcon.prototype.onRemove = function () {
    if (this.div_ && this.div_.parentNode) {
        this.hide();
        this.div_.parentNode.removeChild(this.div_);
        this.div_ = null;
    }
    if (this.chart_div_ && this.chart_div_.parentNode) {
        this.hide();
        this.chart_div_.parentNode.removeChild(this.chart_div_);
        this.chart_div_ = null;
    }
};
ClusterIcon.prototype.setSums = function (sums) {
    this.sums_ = sums;
    this.text_ = sums.text;
    this.index_ = sums.index;
    if (this.div_) {
        this.div_.innerHTML = sums.text;
    }
    this.useStyle();
};
ClusterIcon.prototype.useStyle = function () {
    var index = Math.max(0, this.sums_.index - 1);
    index = Math.min(this.styles_.length - 1, index);
    var style = this.styles_[index];
    //this.url_ = style['url'];
    this.height_ = style['height'];
    this.width_ = style['width'];
    this.textColor_ = style['textColor'];
    this.anchor_ = style['anchor'];
    this.textSize_ = style['textSize'];
    this.backgroundPosition_ = style['backgroundPosition'];
};
ClusterIcon.prototype.setCenter = function (center) {
    this.center_ = center;
};
ClusterIcon.prototype.createCss = function (pos) {
    var style = [];
    style.push('background-image:url(' + this.url_ + ');');
    var backgroundPosition = this.backgroundPosition_ ? this.backgroundPosition_ : '0 0';
    style.push('background-position:' + backgroundPosition + ';');

    if (typeof this.anchor_ === 'object') {
        if (typeof this.anchor_[0] === 'number' && this.anchor_[0] > 0 &&
            this.anchor_[0] < this.height_) {
            style.push('height:' + (this.height_ - this.anchor_[0]) +
                'px; padding-top:' + this.anchor_[0] + 'px;');
        } else {
            style.push('height:' + this.height_ + 'px; line-height:' + this.height_ +
                'px;');
        }
        if (typeof this.anchor_[1] === 'number' && this.anchor_[1] > 0 &&
            this.anchor_[1] < this.width_) {
            style.push('width:' + (this.width_ - this.anchor_[1]) +
                'px; padding-left:' + this.anchor_[1] + 'px;');
        } else {
            style.push('width:' + this.width_ + 'px; text-align:center;');
        }
    } else {
        style.push('height:' + this.height_ + 'px; line-height:' +
            this.height_ + 'px; width:' + this.width_ + 'px; text-align:center;');
    }

    var txtColor = this.textColor_ ? this.textColor_ : 'black';
    var txtSize = this.textSize_ ? this.textSize_ : 11;

    style.push('cursor:pointer; top:' + pos.y + 'px; left:' +
        pos.x + 'px; color:' + txtColor + '; position:absolute; font-size:' +
        txtSize + 'px; font-family:Arial,sans-serif; font-weight:bold');
    return style.join('');
};
window['MarkerClusterer'] = MarkerClusterer;
MarkerClusterer.prototype['addMarker'] = MarkerClusterer.prototype.addMarker;
MarkerClusterer.prototype['addMarkers'] = MarkerClusterer.prototype.addMarkers;
MarkerClusterer.prototype['clearMarkers'] =
    MarkerClusterer.prototype.clearMarkers;
MarkerClusterer.prototype['fitMapToMarkers'] =
    MarkerClusterer.prototype.fitMapToMarkers;
MarkerClusterer.prototype['getCalculator'] =
    MarkerClusterer.prototype.getCalculator;
MarkerClusterer.prototype['getGridSize'] =
    MarkerClusterer.prototype.getGridSize;
MarkerClusterer.prototype['getExtendedBounds'] =
    MarkerClusterer.prototype.getExtendedBounds;
MarkerClusterer.prototype['getMap'] = MarkerClusterer.prototype.getMap;
MarkerClusterer.prototype['getMarkers'] = MarkerClusterer.prototype.getMarkers;
MarkerClusterer.prototype['getMaxZoom'] = MarkerClusterer.prototype.getMaxZoom;
MarkerClusterer.prototype['getStyles'] = MarkerClusterer.prototype.getStyles;
MarkerClusterer.prototype['getLegend'] = MarkerClusterer.prototype.getLegend;
MarkerClusterer.prototype['getTotalClusters'] =
    MarkerClusterer.prototype.getTotalClusters;
MarkerClusterer.prototype['getTotalMarkers'] =
    MarkerClusterer.prototype.getTotalMarkers;
MarkerClusterer.prototype['redraw'] = MarkerClusterer.prototype.redraw;
MarkerClusterer.prototype['removeMarker'] =
    MarkerClusterer.prototype.removeMarker;
MarkerClusterer.prototype['removeMarkers'] =
    MarkerClusterer.prototype.removeMarkers;
MarkerClusterer.prototype['resetViewport'] =
    MarkerClusterer.prototype.resetViewport;
MarkerClusterer.prototype['repaint'] =
    MarkerClusterer.prototype.repaint;
MarkerClusterer.prototype['setCalculator'] =
    MarkerClusterer.prototype.setCalculator;
MarkerClusterer.prototype['setGridSize'] =
    MarkerClusterer.prototype.setGridSize;
MarkerClusterer.prototype['setMaxZoom'] =
    MarkerClusterer.prototype.setMaxZoom;
MarkerClusterer.prototype['onAdd'] = MarkerClusterer.prototype.onAdd;
MarkerClusterer.prototype['draw'] = MarkerClusterer.prototype.draw;

Cluster.prototype['getCenter'] = Cluster.prototype.getCenter;
Cluster.prototype['getSize'] = Cluster.prototype.getSize;
Cluster.prototype['getMarkers'] = Cluster.prototype.getMarkers;

ClusterIcon.prototype['onAdd'] = ClusterIcon.prototype.onAdd;
ClusterIcon.prototype['draw'] = ClusterIcon.prototype.draw;
ClusterIcon.prototype['onRemove'] = ClusterIcon.prototype.onRemove;