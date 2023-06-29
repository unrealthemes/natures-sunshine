const path = require("path");
const config = require("../Settings/config");

const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ImageminWebpWebpackPlugin= require("imagemin-webp-webpack-plugin");
const MiniCssExtractPlugin = require("../Plugins/MiniCssExtractPlugin");
const SpriteLoaderPlugin = require("../Plugins/SpriteLoaderPlugin");

const Scripts = require("../Presets/Scripts");
const Style = require("../Presets/Style");
const Fonts = require("../Presets/Fonts");
const SvgSprite = require("../Presets/SvgSprite");
const Images = require("../Presets/Images");
const {dirs} = require('../Settings/Constants');

module.exports = {
    context: dirs.src,
    target: "web",
    entry: {
        main: path.join(dirs.src, "index.js"),
    },
    output: {
        path: dirs.dist,
        filename: 'js/[name].bundle.js',
        publicPath: "auto",
        assetModuleFilename: '[path][name][ext]'
    },
    resolve: {
        modules: ['node_modules'],
        alias: {
            '@': dirs.src,
            '@js': path.join(dirs.src, "js"),
            '@scss': path.join(dirs.src, "scss"),
            '@images': path.join(dirs.src, "images"),
            '@icons': path.join(dirs.src, "images", "icons"),
            '@sprite': path.join(dirs.src, "images", "sprite"),
            '@fonts': path.join(dirs.src, "fonts"),
        },
    },
    plugins: [
        new BrowserSyncPlugin(
            {
                host: "localhost",
                port: 3030,
                proxy: config.devUrl, // YOUR DEV-SERVER URL (FOUND IN CONFIG)
                files: [
                    "../../../**/*.php",
                    path.join(__dirname, "dist/css/*.css"),
                    path.join(__dirname, "dist/js/*.js"),
                ],
            },
            {
                reload: true,
                injectChanges: true,
            }
        ),
        new MiniCssExtractPlugin({
            filename: "css/style.min.css",
        }),
        new SpriteLoaderPlugin({
            plainSprite: true
        }),
        new ImageminWebpWebpackPlugin({
            config: [{
                test: /\.(jpe?g|png)/,
                // exclude: ["src/images/favicon"],
                options: {
                    quality: 80
                }
            }]
        }),
        new CopyWebpackPlugin({
            patterns: [
                {
                    from: path.join(dirs.src, "images"),
                    to: path.join(dirs.dist, "images"),
                }
            ]
        })
    ],
    module: {
        rules: [
            SvgSprite,
            Scripts,
            Style,
            Fonts,
            Images,
        ],
    }
}