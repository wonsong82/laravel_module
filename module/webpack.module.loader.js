const
    path    = require('path'),
    fs      = require('fs')


// Load module paths if any
module.exports = paths => {
    fs.readdirSync(__dirname).map(moduleName => {
        const modulePathFile = path.join(__dirname, moduleName + '/Assets/webpack.path.js')
        if(fs.existsSync(modulePathFile)){
            const modulePaths = require(modulePathFile)
            Object.keys(modulePaths).map( key => {
                paths.entries[key] = './' + paths.modulePath + '/' + moduleName + '/Assets/' + modulePaths[key]
            })
        }
    })

    return paths
}
