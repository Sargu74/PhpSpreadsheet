import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.getcapacitor.app',
  appName: 'LaborRec',
  webDir: 'www',
  bundledWebRuntime: false,
  android: {
    buildOptions: {
      keystorePath: 'c:Userssargu.keystoreluxilusao.jks',
      keystoreAlias: 'laborrec',
    },
  },
};

export default config;
