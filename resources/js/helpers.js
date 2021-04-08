export function expectedEnvironments(project) {
    if (!project) {
        return []
    }

    const production = {
        name: project.productionBranch,
        branch: project.productionBranch,
        url: '',
        type: 'Production',
        deployments: [],
        tasks: [],
        status: 'New',
    }

    const stages = project.stageBranches.map(stage => ({
        name: stage,
        branch: stage,
        url: '',
        type: 'Stage',
        deployments: [],
        tasks: [],
        status: 'New',
    }))

    // Locator to make applying lagoon info easier
    const stageWithBranch = branch => production.branch === branch ? production : stages.filter(e => e.branch === branch)[0]
    if (project.lagoonProject && project.lagoonProject.environments && project.lagoonProject.environments.length) {
        for (const environment of project.lagoonProject.environments) {
            const stage = stageWithBranch(environment.deployBaseRef)
            if (stage) {
                const deployments = environment.deployments ? [...environment.deployments] : []
                const firstDeployment = deployments[0]


                const statusMap = {
                    'complete': 'Running',
                    'running': 'Deploying',
                    'pending': 'Deploying',
                }

                if (firstDeployment) {
                    stage.status = Object.keys(statusMap).indexOf(firstDeployment.status) !== -1 ? statusMap[firstDeployment.status] : firstDeployment.status
                } else {
                    stage.status = 'Created'
                }
                stage.url = environment.route !== 'undefined' ? environment.route : ''
                stage.deployments = deployments
                stage.tasks = environment.tasks ? [...environment.tasks] : []
            }
        }
    }

    return [
        production,
        ...stages
    ]
}
