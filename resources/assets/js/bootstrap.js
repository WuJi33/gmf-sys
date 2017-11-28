import Vue from 'vue';
import 'regenerator-runtime/runtime'
import MdCss from 'core/MdCss'
import material from './material'
import * as MdComponents from './components'

import lodash from 'lodash';

import VueRouter from 'vue-router';

import ga from 'vue-ga'
import { sync } from 'vuex-router-sync'


import routes from './routes';
import lang from './lang';
import validator from './validator';

import model from './core/mixins/MdModel/MdModel';

import http from './core/utils/http';

import common from './core/utils/common';

import enumCache from './core/utils/enumCache';

window._ = window._ || lodash;

window.Vue = window.Vue || Vue;
window.$modelMixin = model;



let VueMaterial = Vue => {
  material(Vue)
  Object.values(MdComponents).forEach((MdComponent) => {
    Vue.use(MdComponent)
  })

}

VueMaterial.version = '__VERSION__'


function getEntId() {
  return window.gmfEntID;
}

const start = {
  configs: []
};
/*
options:{
  elID,
  routes:[]
}
*/
start.run = function(options) {
  options = options || {};
  const elID = options.elID || '#gmfApp';
  var rootData = {
    title: '',
    userData: { ent: {}, entId: getEntId(), ents: [] },
    userConfig: window.gmfConfig
  };

  const router = { mode: 'history', routes: options.routes && options.routes.length > 0 ? options.routes : routes };

  // ga(router, 'UA-85823257-1')
  // sync(store, router)

  baseConfig();

  //run configs
  for (var i = 0; i < this.configs.length; i++) {
    this.configs[i] && this.configs[i](Vue);
  }
  Vue.use(VueRouter);
  Vue.use(VueMaterial);

  const app = new Vue({
    router: new VueRouter(router),
    el: elID,
    data: rootData,
    watch: {
      "userData.ent": function(v, o) {
        this.userData.entId = v ? v.id : "";
      },
      "userData.entId": function(v, o) {
        this.setHttpConfig();
        window.gmfEntID = v;
      },
      "userConfig": function(v, o) {
        window.gmfConfig = v;
      }
    },
    computed: {

    },
    methods: {
      setHttpConfig() {
        this.$http.defaults.headers.common.Ent = this.userData.entId;
      },
      loadEnums() {
        this.$http.get('sys/enums/all').then(response => {
          if (response.data && response.data.data) {
            response.data.data.forEach((item) => {
              this.setCacheEnum(item);
            });
          }
        }, response => {
          console.log(response);
        });
      },
      setCacheEnum(item) {
        enumCache.set(item);
      },
      getCacheEnum(type) {
        return enumCache.get(type);
      },
      getCacheEnumName(type, item) {
        return enumCache.getEnumName(type, item);
      },
      loadEnts() {
        this.$http.get('sys/ents/my').then(response => {
          this.userData.ents = response.data.data || [];
          var ent = false;
          _.forEach(this.userData.ents, (value, key) => {
            if (value.id == this.userData.entId) {
              ent = value;
            }
          });
          if (!ent) {
            _.forEach(this.userData.ents, function(value, key) {
              if (value.is_default) {
                ent = value;
              }
            });
          }
          if (!ent && this.userData.ents && this.userData.ents.length > 0) {
            ent = this.userData.ents[0];
          }
          this.userData.ent = ent;
        }, response => {
          console.log(response);
        });
      },
      async issueUid(node, num) {
        try {
          const response = await this.$http.get('sys/uid', { params: { node: node, num: num } });
          return response.data.data;
        } catch (error) {
          return false;
        }
        return false;
      },
    },
    created: function() {
      this.loadEnts();
      this.loadEnums();
    }
  });
};
start.config = function(callback) {
  this.configs.push(callback);
};

function baseConfig() {
  http.defaults.baseURL = '/api';
  http.defaults.headers = { common: { Ent: getEntId() } };
  Vue.prototype.$http = http;

  Vue.prototype._ = lodash;
  Vue.prototype.$toast = function(toast) {
    this.$root.$refs.rootToast.toast(toast);
  }
  Vue.prototype.$lang = lang;
  Vue.prototype.$validate = function(input, rules, customMessages) {
    return new validator(input, rules, customMessages);
  };
  Vue.prototype.$goID = function(id, options, isReplace) {
    var localtion = { name: 'id', params: { id: id } };
    isReplace = !!isReplace;
    localtion = common.merge(localtion, options);
    this.$router && this.$router[isReplace ? 'replace' : 'push'](localtion);
  };
  Vue.prototype.$goModule = function(module, options, isReplace) {
    var localtion = { name: 'module', params: { module: module } };
    isReplace = !!isReplace;
    localtion = common.merge(localtion, options);
    this.$router && this.$router[isReplace ? 'replace' : 'push'](localtion);
  };
  Vue.prototype.$goApp = function(app, options, isReplace) {
    var localtion = { name: 'app' };
    isReplace = !!isReplace;
    localtion = common.merge(localtion, options, { params: { app: app } });
    this.$router && this.$router[isReplace ? 'replace' : 'push'](localtion);
  };
  Vue.prototype.$documentTitle = function(title) {
    document.title = title;
    this.$root.title = title;
  };
};
export { start, VueMaterial };
