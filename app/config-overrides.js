const { override, useEslintRc, applyWebpackConfig } = require('customize-cra');

module.exports = override(
    (config, env) => {
        // 修改 JS 文件名
        config.output.filename = 'my-app-[name].v5.js';
        config.output.chunkFilename = 'my-app-[name].chunk.js';

        // 修改 CSS 文件名
        config.plugins[5].options.filename = 'my-app-[name].v3.css';
        config.plugins[5].options.chunkFilename = 'my-app-[name].chunk.css';

        return config;
    }
);

